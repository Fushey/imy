from flask import Flask, request, jsonify, send_file, current_app, url_for, Response, render_template_string, Blueprint
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy import func, extract

from werkzeug.security import generate_password_hash, check_password_hash
from functools import wraps
import jwt
import datetime
from flask_cors import CORS
from werkzeug.utils import secure_filename
import os
import json
import zipfile
import io
from reportlab.pdfgen import canvas
from reportlab.lib.pagesizes import letter
from reportlab.lib import colors
from reportlab.lib.pagesizes import letter
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle, Paragraph, Spacer
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.units import inch
from datetime import datetime  # Changed this line
from PIL import Image as PILImage
from reportlab.graphics.shapes import Drawing, Line
from reportlab.lib.colors import HexColor
from reportlab.lib.pagesizes import A4
from reportlab.lib.units import mm
from datetime import datetime, timedelta
from sqlalchemy import func
import logging
from reportlab.lib.enums import TA_RIGHT, TA_CENTER, TA_LEFT
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
from flask_mail import Mail, Message
from dotenv import load_dotenv
from smtplib import SMTPException
import socket
import sys
import requests
from sqlalchemy.exc import IntegrityError
import queue  # Add this import
from flask_apscheduler import APScheduler
from functools import wraps
from collections import defaultdict



logging.basicConfig(level=logging.DEBUG)
scheduler = APScheduler()


def with_app_context(f):
    @wraps(f)
    def decorated(*args, **kwargs):
        with app.app_context():
            return f(*args, **kwargs)
    return decorated


@with_app_context
def check_unread_messages():
    ten_minutes_ago = datetime.utcnow() - timedelta(minutes=1)
    unread_messages = ChatMessage.query.filter(
        ChatMessage.is_read == False,
        ChatMessage.is_user_message == False,
        ChatMessage.timestamp <= ten_minutes_ago,
        ChatMessage.notified == False
    ).all()

    user_messages = defaultdict(list)
    for message in unread_messages:
        user_messages[message.user_id].append(message)

    emails_sent = []
    for user_id, messages in user_messages.items():
        user = User.query.get(user_id)
        if user and user.email:
            send_unread_messages_notification(user.email, messages)
            emails_sent.append(user.email)
            for message in messages:
                message.notified = True
                db.session.add(message)
    
    db.session.commit()

    if emails_sent:
        for email in emails_sent:
            logger.info(f"Email sent to {email}")
    else:
        logger.info("NO EMAILS SENT")



app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "https://auftrag.immoyes.com"}})
UPLOAD_FOLDER = '/var/www/auftrag.immoyes.com/upload'
FINALIZED_UPLOAD_FOLDER = '/var/www/auftrag.immoyes.com/finalized_uploads'
ALLOWED_EXTENSIONS = {'jpg', 'jpeg', 'png', 'gif', 'heic'}

app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "https://auftrag.immoyes.com"}})
CORS(app, supports_credentials=True, origins=["https://auftrag.immoyes.com", "http://127.0.0.1"])

# Configuration
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+pymysql://d0414046:WS2A99X53jMsvsD7jWeV@w0108f4a.kasserver.com/d0414046'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
app.config['SECRET_KEY'] = 'your_secret_key'  # Change this!
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER
app.config['FINALIZED_UPLOAD_FOLDER'] = FINALIZED_UPLOAD_FOLDER

scheduler = APScheduler()

db = SQLAlchemy(app)

def allowed_file(filename):
    return '.' in filename and \
           filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

class User(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(80), unique=True, nullable=False)
    email = db.Column(db.String(120), unique=True, nullable=False)
    password = db.Column(db.String(255), nullable=False)
    is_admin = db.Column(db.Boolean, default=False)
    credits = db.Column(db.Float, default=0.0)
    is_operator = db.Column(db.Boolean, nullable=False, default=False)
    vorname = db.Column(db.String(50), nullable=True)
    nachname = db.Column(db.String(50), nullable=True)
    adresse = db.Column(db.String(200), nullable=True)
    zip = db.Column(db.String(10), nullable=True)
    city = db.Column(db.String(100), nullable=True)

    # Relationship
    messages = db.relationship('ChatMessage', backref='user', lazy='dynamic')

    def __repr__(self):
        return f'<User {self.username}>'

    def to_dict(self):
        return {
            'id': self.id,
            'username': self.username,
            'email': self.email,
            'is_admin': self.is_admin,
            'credits': self.credits,
            'is_operator': self.is_operator,
            'vorname': self.vorname,
            'nachname': self.nachname,
            'adresse': self.adresse,
            'zip': self.zip,
            'city': self.city
        }
    
  

class Project(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), nullable=False)
    description = db.Column(db.Text)
    user_id = db.Column(db.Integer, db.ForeignKey('user.id'), nullable=False)
    status = db.Column(db.String(20), default='In Bearbeitung')
    cost = db.Column(db.Float, default=0.0)
    project_type = db.Column(db.String(50))
    furniture_style = db.Column(db.String(50))
    image_links = db.Column(db.Text)  # Store as JSON string
    final_image_links = db.Column(db.Text)  # New field for final images
    created_at = db.Column(db.DateTime, default=db.func.current_timestamp())

class Image(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    project_id = db.Column(db.Integer, db.ForeignKey('project.id'), nullable=False)
    file_path = db.Column(db.String(255), nullable=False)
    room_type = db.Column(db.String(50))
    notes = db.Column(db.Text)

class Revision(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    project_id = db.Column(db.Integer, db.ForeignKey('project.id'), nullable=False)
    image_id = db.Column(db.Integer, db.ForeignKey('image.id'), nullable=True)  # Add this line

    revision_text = db.Column(db.Text, nullable=False)
    created_at = db.Column(db.DateTime, default=db.func.current_timestamp())

class Transaction(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('user.id'), nullable=False)
    transaction_id = db.Column(db.String(100), unique=True, nullable=False)
    credits = db.Column(db.Float, nullable=False)
    amount = db.Column(db.Float, nullable=False)
    timestamp = db.Column(db.DateTime, default=db.func.current_timestamp())

class ChatMessage(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('user.id'), nullable=False)
    message = db.Column(db.String(500), nullable=False)
    timestamp = db.Column(db.DateTime, default=datetime.utcnow)
    is_user_message = db.Column(db.Boolean, default=True)
    is_read = db.Column(db.Boolean, default=False)
    notified = db.Column(db.Boolean, default=False)
    is_auto_reply = db.Column(db.Boolean, default=False)


    def __repr__(self):
        return f'<ChatMessage {self.id} from user {self.user_id}>'

    def to_dict(self):
        return {
            'id': self.id,
            'user_id': self.user_id,
            'message': self.message,
            'timestamp': self.timestamp.isoformat(),
            'is_user_message': self.is_user_message,
            'is_read': self.is_read
        }

    def to_dict(self):
        return {
            'id': self.id,
            'transaction_id': self.transaction_id,
            'credits': self.credits,
            'amount': self.amount,
            'timestamp': self.timestamp.isoformat()
        }
    
# Set up logging
logging.basicConfig(level=logging.WARNING)  # Set the root logger to WARNING level
logger = logging.getLogger(__name__)
logging.getLogger('apscheduler').setLevel(logging.WARNING)

def init_scheduler():
    scheduler.init_app(app)
    
    # Check if the job already exists
    existing_job = scheduler.get_job('check_unread_messages')
    if existing_job:
        # If the job exists, remove it
        scheduler.remove_job('check_unread_messages')
    
    # Add the job
    scheduler.add_job(id='check_unread_messages', func=check_unread_messages, trigger='interval', minutes=10 )
    
    # Start the scheduler if it's not already running
    if not scheduler.running:
        scheduler.start()
    

# Email configuration
# Configure Flask-Mail
app.config['MAIL_SERVER'] = 'w0108f4a.kasserver.com'
app.config['MAIL_PORT'] = 465
app.config['MAIL_USE_TLS'] = False
app.config['MAIL_USE_SSL'] = True
app.config['MAIL_USERNAME'] = 'portal@immoyes.com'
app.config['MAIL_PASSWORD'] = '2qKt6nZDtmdseZ3FG92A'

# Initialize extensions
mail = Mail(app)

    # Register a nice, professional font (ensure you have the rights to use this font)
pdfmetrics.registerFont(TTFont('Roboto', 'fonts/Roboto-Regular.ttf'))
pdfmetrics.registerFont(TTFont('Roboto-Bold', 'fonts/Roboto-Bold.ttf'))

def token_required(f):
    @wraps(f)
    def decorated(*args, **kwargs):
        token = None
        if 'Authorization' in request.headers:
            token = request.headers['Authorization'].split(" ")[1]
        if not token:
            return jsonify({'message': 'Token is missing!'}), 401
        try:
            data = jwt.decode(token, app.config['SECRET_KEY'], algorithms=["HS256"])
            current_user = User.query.filter_by(id=data['user_id']).first()
        except:
            return jsonify({'message': 'Token is invalid!'}), 401
        return f(current_user, *args, **kwargs)
    return decorated



# Configure Flask-Mail
app.config['MAIL_SERVER'] = 'w0108f4a.kasserver.com'
app.config['MAIL_PORT'] = 587
app.config['MAIL_USE_TLS'] = True
app.config['MAIL_USERNAME'] = 'portal@immoyes.com'
app.config['MAIL_PASSWORD'] = os.getenv('MAIL_PASSWORD')

# Routes


# Add these imports if not already present
from werkzeug.utils import secure_filename
import os
import json






# Token validation function
def validate_token(token):
    try:
        payload = jwt.decode(token, current_app.config['SECRET_KEY'], algorithms=['HS256'])
        if datetime.utcfromtimestamp(payload['exp']) < datetime.utcnow():
            return None
        user = User.query.get(payload['user_id'])
        return user if user else None
    except (jwt.ExpiredSignatureError, jwt.InvalidTokenError):
        return None





@with_app_context
def send_unread_messages_notification(user_email, messages):
    try:
        # Filter out auto-reply messages
        non_auto_reply_messages = [msg for msg in messages if not getattr(msg, 'is_auto_reply', False)]

        # If there are no non-auto-reply messages, don't send an email
        if not non_auto_reply_messages:
            return

        subject = "Immo Yes - Sie haben neue ungelesene Nachrichten"
        sender = current_app.config['MAIL_USERNAME']
        recipients = [user_email]

        # HTML template for the email in German
        html_template = """
        <!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Benachrichtigung über ungelesene Nachrichten</title>
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f4f4f4; padding: 20px;">
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0" border="0" width="600" style="margin: 0 auto; background-color: #ffffff; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <tr>
                                <td style="padding: 20px; text-align: center;">
                                    <img src="cid:logo" alt="ImmoYes Logo" style="max-width: 200px; height: auto;">
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 20px;">
                                    <h1 style="color: #2c3e50; margin-bottom: 20px;">Sie haben ungelesene Nachrichten</h1>
                                    <p style="font-size: 16px; margin-bottom: 20px;">Sie haben {message_count} neue Nachrichten erhalten.</p>
                                    <div style="background-color: #f8f8f8; border-left: 4px solid #2980b9; padding: 15px; margin-bottom: 20px;">
                                        {message_list}
                                    </div>
                                    <a href="http://auftrag.immoyes.com/index.php?page=chat" style="display: inline-block; background-color: #2980b9; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 3px;">Nachrichten anzeigen</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #34495e; color: #ffffff; padding: 20px; text-align: center;">
                                    <p style="margin: 0;">&copy; 2024 ImmoYes. Alle Rechte vorbehalten.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        """

        message_list_html = "".join([f"<p style='font-style: italic; color: #34495e; margin-bottom: 10px;'>{msg.message}</p>" for msg in non_auto_reply_messages])

        msg = Message(subject=subject,
                      sender=sender,
                      recipients=recipients)
        
        # Format the HTML template with the message content
        msg.html = html_template.format(message_count=len(non_auto_reply_messages), message_list=message_list_html)
        
        # Attach the logo
        with current_app.open_resource("static/logo.png") as logo:
            msg.attach("logo.png", "image/png", logo.read(), "inline", headers={"Content-ID": "<logo>"})
        
        mail.send(msg)
        
    except Exception as e:
        logger.error(f"Error sending email to {user_email}: {str(e)}")





# In-memory queues for messages
user_queues = {}
operator_queues = {}

# Add this constant at the top of your file
AUTO_REPLY_THRESHOLD = timedelta(minutes=1)  # Adjust this value as needed

@app.route('/api/chat', methods=['POST'])
@token_required
def send_message(current_user):
    data = request.json
    message = data.get('message')
    
    if not message:
        return jsonify({'error': 'Message is required'}), 400

    new_message = ChatMessage(
        user_id=current_user.id, 
        message=message, 
        is_user_message=True,
        is_read=True
    )
    db.session.add(new_message)
    db.session.commit()

    # Notify user about their own message
    user_queue = user_queues.get(current_user.id)
    if user_queue:
        user_queue.put({
            'id': new_message.id,
            'user_id': new_message.user_id,
            'message': new_message.message,
            'timestamp': new_message.timestamp.isoformat(),
            'is_user_message': new_message.is_user_message,
            'is_read': new_message.is_read
        })

    # Notify operators about the new message
    handle_new_user_message(new_message)

    # Check if we need to send an auto-reply
    last_operator_message = ChatMessage.query.filter_by(
        user_id=current_user.id,
        is_user_message=False
    ).order_by(ChatMessage.timestamp.desc()).first()

    send_auto_reply = False
    if not last_operator_message or \
       (datetime.utcnow() - last_operator_message.timestamp) > AUTO_REPLY_THRESHOLD:
        send_auto_reply = True

    if send_auto_reply:
        auto_reply = ChatMessage(
            user_id=current_user.id,
            message="Vielen Dank fuer Ihre Nachricht. Wir melden uns umgehnend zurueck. Wir informieren Sie ebenfalls per E-Mail. Vielen Dank Ihr Immo Yes Team",
            is_user_message=False,
            is_read=False,
            is_auto_reply=True
        )
        db.session.add(auto_reply)
        db.session.commit()

        # Notify the user about the auto-reply
        if user_queue:
            user_queue.put({
                'id': auto_reply.id,
                'user_id': auto_reply.user_id,
                'message': auto_reply.message,
                'timestamp': auto_reply.timestamp.isoformat(),
                'is_user_message': auto_reply.is_user_message,
                'is_read': auto_reply.is_read,
                'is_auto_reply': auto_reply.is_auto_reply
            })

    return jsonify({'success': True}), 200


@app.route('/api/chat/history', methods=['GET'])
@token_required
def get_chat_history(current_user):
    messages = ChatMessage.query.filter_by(user_id=current_user.id).order_by(ChatMessage.timestamp).all()
    
    # Mark all fetched messages as read
    for message in messages:
        if not message.is_user_message and not message.is_read:
            message.is_read = True
    
    db.session.commit()
    
    return jsonify([{
        'id': message.id,
        'user_id': message.user_id,
        'message': message.message,
        'timestamp': message.timestamp.isoformat(),
        'is_user_message': message.is_user_message,
        'is_read': message.is_read
    } for message in messages]), 200

@app.route('/api/chat/stream')
def stream():
    token = request.args.get('token')
    if not token:
        return jsonify({'message': 'Token is missing!'}), 401
    
    try:
        current_user = validate_token(token)
        if current_user is None:
            return jsonify({'message': 'Token is invalid!'}), 401
    except Exception as e:
        return jsonify({'message': 'Token is invalid!'}), 401

    def event_stream():
        user_queue = user_queues.setdefault(current_user.id, queue.Queue())
        while True:
            try:
                message = user_queue.get(timeout=20)  # Wait for 20 seconds
                yield f"data: {json.dumps(message)}\n\n"
            except queue.Empty:
                yield ": keepalive\n\n"  # Send a keepalive message

    return Response(event_stream(), content_type='text/event-stream')

def handle_new_user_message(message):
    for operator_id, operator_queue in operator_queues.items():
        operator_queue.put({
            'id': message.id,
            'user_id': message.user_id,
            'message': message.message,
            'timestamp': message.timestamp.isoformat(),
            'is_user_message': message.is_user_message,
            'is_read': message.is_read
        })

@app.route('/api/operator/chats', methods=['GET'])
@token_required
def get_active_chats(current_user):
    if not current_user.is_operator:
        return jsonify({'error': 'Unauthorized'}), 403

    active_chats = db.session.query(ChatMessage.user_id).distinct().all()
    return jsonify([{'user_id': chat.user_id} for chat in active_chats]), 200

@app.route('/api/operator/chat/<int:user_id>', methods=['GET'])
@token_required
def get_user_chat(current_user, user_id):
    if not current_user.is_operator:
        return jsonify({'error': 'Unauthorized'}), 403

    messages = ChatMessage.query.filter_by(user_id=user_id).order_by(ChatMessage.timestamp).all()
    return jsonify([{
        'id': message.id,
        'user_id': message.user_id,
        'message': message.message,
        'timestamp': message.timestamp.isoformat(),
        'is_user_message': message.is_user_message,
        'is_read': message.is_read
    } for message in messages]), 200

@app.route('/api/operator/reply', methods=['POST'])
@token_required
def operator_reply(current_user):
    if not current_user.is_operator:
        return jsonify({'error': 'Unauthorized'}), 403

    data = request.json
    user_id = data.get('user_id')
    message = data.get('message')
    
    if not user_id or not message:
        return jsonify({'error': 'User ID and message are required'}), 400

    new_message = ChatMessage(
        user_id=user_id, 
        message=message, 
        is_user_message=False,
        is_read=False  # Mark operator's message as unread
    )
    db.session.add(new_message)
    db.session.commit()

    # Add message to user's queue for real-time update
    user_queue = user_queues.get(user_id)
    if user_queue:
        user_queue.put({
            'id': new_message.id,
            'user_id': new_message.user_id,
            'message': new_message.message,
            'timestamp': new_message.timestamp.isoformat(),
            'is_user_message': new_message.is_user_message,
            'is_read': new_message.is_read
        })

    # Also notify the operator who sent the message
    operator_queue = operator_queues.get(current_user.id)
    if operator_queue:
        operator_queue.put({
            'id': new_message.id,
            'user_id': new_message.user_id,
            'message': new_message.message,
            'timestamp': new_message.timestamp.isoformat(),
            'is_user_message': new_message.is_user_message,
            'is_read': new_message.is_read
        })

    return jsonify({'success': True}), 200

@app.route('/api/operator/stream')
def operator_stream():
    token = request.args.get('token')
    if not token:
        return jsonify({'message': 'Token is missing!'}), 401
    
    try:
        current_user = validate_token(token)
        if current_user is None or not current_user.is_operator:
            return jsonify({'message': 'Token is invalid or user is not an operator!'}), 401
    except Exception as e:
        return jsonify({'message': 'Token is invalid!'}), 401

    def event_stream():
        operator_queue = operator_queues.setdefault(current_user.id, queue.Queue())
        while True:
            message = operator_queue.get()
            yield f"data: {json.dumps(message)}\n\n"

    return Response(event_stream(), content_type='text/event-stream')

def handle_new_user_message(message):
    for operator_id, operator_queue in operator_queues.items():
        operator_queue.put({
            'id': message.id,
            'user_id': message.user_id,
            'message': message.message,
            'timestamp': message.timestamp.isoformat(),
            'is_user_message': message.is_user_message,
            'is_read': message.is_read
        })

@app.route('/api/chat/unread', methods=['GET'])
@token_required
def get_unread_message_count(current_user):
    unread_count = ChatMessage.query.filter_by(
        user_id=current_user.id, 
        is_user_message=False, 
        is_read=False
    ).count()
    
    return jsonify({'unreadCount': unread_count}), 200

@app.route('/api/chat/mark-read', methods=['POST'])
@token_required
def mark_messages_as_read(current_user):
    ChatMessage.query.filter_by(
        user_id=current_user.id, 
        is_user_message=False, 
        is_read=False
    ).update({'is_read': True})
    
    db.session.commit()
    
    return jsonify({'success': True}), 200






@app.route('/logout', methods=['POST'])
@token_required
def logout(current_user):
    token = request.headers['Authorization'].split(" ")[1]
    invalidated_tokens.add(token)
    return jsonify({'message': 'Successfully logged out'}), 200

# Optional: Cleanup function to remove expired tokens from the invalidated_tokens set
def cleanup_invalidated_tokens():
    global invalidated_tokens
    current_time = datetime.utcnow()
    invalidated_tokens = {token for token in invalidated_tokens if jwt.decode(token, app.config['SECRET_KEY'], algorithms=["HS256"])['exp'] > current_time.timestamp()}

@app.route('/register', methods=['POST'])
def register():
    data = request.get_json()
    username = data.get('username')
    email = data.get('email')
    password = data.get('password')

    # Basic input validation
    if not username or not email or not password:
        return jsonify({'message': 'Username, email, and password are required'}), 400

    # Hash the password before storing it
    hashed_password = generate_password_hash(password)

    # Create a new user instance
    new_user = User(
        username=username,
        email=email,
        password=hashed_password
    )

    try:
        # Add and commit the new user to the database
        db.session.add(new_user)
        db.session.commit()

        return jsonify({
            'message': 'User registered successfully',
            'user_id': new_user.id,
            'username': new_user.username,
            'email': new_user.email,
            'credits': new_user.credits,
            'is_admin': new_user.is_admin
        }), 201

    except IntegrityError:
        db.session.rollback()
        return jsonify({'message': 'Username or email already exists'}), 409

@app.route('/login', methods=['POST'])
def login():
    data = request.get_json()
    username = data.get('username')
    password = data.get('password')

    if not username or not password:
        return jsonify({'message': 'Username and password are required'}), 400

    user = User.query.filter_by(username=username).first()

    if user and check_password_hash(user.password, password):
        token = jwt.encode({
            'user_id': user.id,
            'exp': datetime.utcnow() + timedelta(hours=24)
        }, app.config['SECRET_KEY'], algorithm="HS256")

        return jsonify({
            'message': 'Logged in successfully',
            'token': token,
            'user_id': user.id,
            'username': user.username,
            'email': user.email,
            'credits': user.credits,
            'is_admin': user.is_admin
        }), 200
    else:
        return jsonify({'message': 'Invalid username or password'}), 401

# Add this new route for 3D Floorplans
@app.route('/api/3d-floorplan', methods=['POST'])
@token_required
def submit_3d_floorplan(current_user):
    try:
        app.logger.info(f"Received 3D floorplan request. Form data: {request.form}")
        app.logger.info(f"Files received: {request.files}")

        if 'files' not in request.files:
            app.logger.error("No file part in the request")
            return jsonify({'error': 'No file part'}), 400
        
        files = request.files.getlist('files')
        app.logger.info(f"Number of files received: {len(files)}")

        job_title = request.form.get('jobTitle')
        total_price = request.form.get('totalPrice')
        express_service = request.form.get('expressService') == 'true'
        furniture_customization = request.form.get('furnitureCustomization') == 'true'

        app.logger.info(f"Job Title: {job_title}")
        app.logger.info(f"Total Price: {total_price}")
        app.logger.info(f"Express Service: {express_service}")
        app.logger.info(f"Furniture Customization: {furniture_customization}")
        
        if not job_title or not total_price:
            missing = []
            if not job_title: missing.append('job title')
            if not total_price: missing.append('total price')
            app.logger.error(f"Missing: {', '.join(missing)}")
            return jsonify({'error': f"Missing: {', '.join(missing)}"}), 400

        try:
            total_price = float(total_price)
        except ValueError:
            app.logger.error(f"Invalid total price: {total_price}")
            return jsonify({'error': 'Invalid total price'}), 400

        if current_user.credits < total_price:
            app.logger.error(f"Insufficient credits. User has {current_user.credits}, needed {total_price}")
            return jsonify({'error': 'Insufficient credits'}), 400

        new_project = Project(
            name=job_title,
            description="3D Floorplan",
            user_id=current_user.id,
            status='In Bearbeitung',
            cost=total_price,
            project_type='3d_floorplan',
            created_at=datetime.utcnow()
        )

        db.session.add(new_project)
        db.session.flush()  # This will assign an ID to new_project
        app.logger.info(f"Created new project with ID: {new_project.id}")

        # Define the base upload folder
        base_upload_folder = '/var/www/auftrag.immoyes.com/upload'

        # Create a folder for the user based on their email and project ID
        user_folder = os.path.join(base_upload_folder, current_user.email)
        project_folder = os.path.join(user_folder, str(new_project.id))
        os.makedirs(project_folder, exist_ok=True)
        app.logger.info(f"Created project folder: {project_folder}")

        image_links = []
        floor_details = []
        for i, file in enumerate(files):
            if file and allowed_file(file.filename):
                filename = secure_filename(file.filename)
                file_path = os.path.join(project_folder, filename)
                file.save(file_path)
                app.logger.info(f"Saved file: {file_path}")
                image_links.append(filename)  # Only append the filename, not the full path

                floor = request.form.get(f'floors[{i}]', '')
                notes = request.form.get(f'notes[{i}]', '')

                floor_details.append({
                    'floor': floor,
                    'notes': notes
                })

                new_image = Image(
                    project_id=new_project.id,
                    file_path=filename,  # Only store the filename, not the full path
                    room_type=floor,  # Using room_type to store floor information
                    notes=notes
                )
                db.session.add(new_image)
                app.logger.info(f"Added new image to database: {filename}")
            else:
                app.logger.error(f"Invalid file: {file.filename}")
                return jsonify({'error': f'Invalid file: {file.filename}'}), 400

        new_project.image_links = json.dumps(image_links)
        app.logger.info(f"Added image links to project: {image_links}")
        
        current_user.credits -= total_price
        app.logger.info(f"Updated user credits. New balance: {current_user.credits}")

        db.session.commit()
        app.logger.info("Committed changes to database")

        user_identifier = current_user.email

        # Send email to admin
        admin_subject = f"New 3D Floorplan Project: {len(files)} pictures, ${total_price:.2f}, {user_identifier}"
        admin_body = f"""
        New 3D floorplan project submitted:

        Project ID: {new_project.id}
        Job Title: {job_title}
        Customer: {user_identifier}
        Total Price: ${total_price:.2f}
        Number of Images: {len(files)}
        Express Service: {'Yes' if express_service else 'No'}
        Furniture Customization: {'Yes' if furniture_customization else 'No'}

        Floor Details:
        """

        for i, floor in enumerate(floor_details):
            admin_body += f"\n- Floor {i+1}: {floor['floor']}\n  Notes: {floor['notes']}"

        admin_msg = Message(admin_subject,
                      sender=app.config['MAIL_USERNAME'],
                      recipients=['jansen.tobias@gmail.com'])
        admin_msg.body = admin_body
        mail.send(admin_msg)
        app.logger.info("Sent admin email")

        # Send email to customer
        customer_subject = "Neues 3D Floorplan Projekt erstellt"
        customer_html = f"""
        <!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Neues 3D Floorplan Projekt erstellt</title>
            <style>
                body {{ font-family: Arial, sans-serif; line-height: 1.6; color: #333; }}
                .container {{ max-width: 600px; margin: 0 auto; padding: 20px; }}
                .logo {{ text-align: center; margin-bottom: 20px; }}
                .button {{
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: #ffffff !important;
                    text-decoration: none;
                    border-radius: 5px;
                    font-weight: bold;
                }}
                .button:hover {{ background-color: #0056b3; }}
            </style>
        </head>
        <body>
            <div class="container">
                <div class="logo">
                    <img src="cid:logo" alt="ImmoYes Logo" style="max-width: 200px;">
                </div>
                <h1>Vielen Dank für Ihr neues 3D Floorplan Projekt!</h1>
                <p>Sehr geehrte(r) Kunde(in),</p>
                <p>Wir freuen uns, Ihnen mitteilen zu können, dass Ihr neues 3D Floorplan Projekt erfolgreich erstellt wurde.</p>
                <p>Sie können Ihr Projekt jederzeit über den folgenden Link einsehen:</p>
                <p style="text-align: center;">
                    <a href="http://auftrag.immoyes.com/index.php?page=project-details&id={new_project.id}" class="button" style="color: #ffffff !important;">Projekt ansehen</a>
                </p>
                <p>Wir werden Sie über den Fortschritt Ihres Projekts auf dem Laufenden halten. Bei Fragen stehen wir Ihnen gerne zur Verfügung.</p>
                <p>Mit freundlichen Grüßen,<br>Ihr ImmoYes Team</p>
            </div>
        </body>
        </html>
        """

        customer_msg = Message(customer_subject,
                               sender=app.config['MAIL_USERNAME'],
                               recipients=[current_user.email],
                               bcc=['jansen.tobias@gmail.com'])
        customer_msg.html = customer_html

        # Attach logo image
        with app.open_resource("static/logo.png", "rb") as logo:
            customer_msg.attach("logo.png", "image/png", logo.read(), "inline", headers={'Content-ID': '<logo>'})

        mail.send(customer_msg)
        app.logger.info("Sent customer email")

        app.logger.info("3D floorplan job submitted successfully and emails sent")
        return jsonify({
            'message': '3D floorplan job submitted successfully',
            'project_id': new_project.id,
            'remaining_credits': current_user.credits
        }), 201
    except Exception as e:
        app.logger.error(f"Error in submit_3d_floorplan: {str(e)}")
        app.logger.exception("Full traceback:")
        db.session.rollback()
        return jsonify({'error': str(e)}), 500

@app.route('/api/3d-floorplan/draft', methods=['POST'])
@token_required
def save_3d_floorplan_draft(current_user):
    try:
        app.logger.info(f"Received 3D floorplan draft request. Form data: {request.form}")
        app.logger.info(f"Files received: {request.files}")

        if 'files' not in request.files:
            app.logger.error("No file part in the request")
            return jsonify({'error': 'No file part'}), 400
        
        files = request.files.getlist('files')
        app.logger.info(f"Number of files received: {len(files)}")

        job_title = request.form.get('jobTitle')
        total_price = request.form.get('totalPrice')
        express_service = request.form.get('expressService') == 'true'
        furniture_customization = request.form.get('furnitureCustomization') == 'true'

        app.logger.info(f"Job Title: {job_title}")
        app.logger.info(f"Total Price: {total_price}")
        app.logger.info(f"Express Service: {express_service}")
        app.logger.info(f"Furniture Customization: {furniture_customization}")
        
        if not job_title:
            app.logger.error("Missing job title")
            return jsonify({'error': 'Missing job title'}), 400
        if not total_price:
            app.logger.error("Missing total price")
            return jsonify({'error': 'Missing total price'}), 400

        try:
            total_price = float(total_price)
        except ValueError:
            app.logger.error(f"Invalid total price: {total_price}")
            return jsonify({'error': 'Invalid total price'}), 400

        new_project = Project(
            name=job_title,
            description="3D Floorplan",
            user_id=current_user.id,
            status='entwurf',
            cost=total_price,
            project_type='3d_floorplan',
            created_at=datetime.utcnow()
        )

        db.session.add(new_project)
        db.session.flush()  # This will assign an ID to new_project
        app.logger.info(f"Created new draft project with ID: {new_project.id}")

        # Define the base upload folder
        base_upload_folder = '/var/www/auftrag.immoyes.com/upload'

        # Create a folder for the user based on their email and project ID
        user_folder = os.path.join(base_upload_folder, current_user.email)
        project_folder = os.path.join(user_folder, str(new_project.id))
        os.makedirs(project_folder, exist_ok=True)
        app.logger.info(f"Created project folder: {project_folder}")

        image_links = []
        for i, file in enumerate(files):
            if file and allowed_file(file.filename):
                filename = secure_filename(file.filename)
                file_path = os.path.join(project_folder, filename)
                file.save(file_path)
                app.logger.info(f"Saved file: {file_path}")
                image_links.append(filename)  # Only append the filename, not the full path

                floor = request.form.get(f'floors[{i}]', '')
                notes = request.form.get(f'notes[{i}]', '')

                new_image = Image(
                    project_id=new_project.id,
                    file_path=filename,  # Only store the filename, not the full path
                    room_type=floor,  # Using room_type to store floor information
                    notes=notes
                )
                db.session.add(new_image)
                app.logger.info(f"Added new image to database: {filename}")
            else:
                app.logger.error(f"Invalid file: {file.filename}")
                return jsonify({'error': f'Invalid file: {file.filename}'}), 400

        new_project.image_links = json.dumps(image_links)
        app.logger.info(f"Added image links to project: {image_links}")

        db.session.commit()
        app.logger.info("Committed changes to database")

        app.logger.info("3D floorplan job saved as draft successfully")
        return jsonify({
            'message': '3D floorplan job saved as draft successfully',
            'project_id': new_project.id
        }), 201
    except Exception as e:
        app.logger.error(f"Error in save_3d_floorplan_draft: {str(e)}")
        app.logger.exception("Full traceback:")
        db.session.rollback()
        return jsonify({'error': str(e)}), 500

# Update the allowed_file function to include PDF
def allowed_file(filename):
    return '.' in filename and \
           filename.rsplit('.', 1)[1].lower() in {'jpg', 'jpeg', 'png', 'gif', 'heic', 'pdf'}







import sys
from flask import jsonify, request
from flask_mail import Message
from werkzeug.utils import secure_filename
import os
from datetime import datetime
import json


@app.route('/api/virtual-renovation/draft', methods=['POST'])
@token_required
def save_virtual_renovation_draft(current_user):
    try:
        app.logger.info(f"Received virtual renovation draft request. Form data: {request.form}")
        app.logger.info(f"Files received: {request.files}")

        if 'files' not in request.files:
            app.logger.error("No file part in the request")
            return jsonify({'error': 'No file part'}), 400
        
        files = request.files.getlist('files')
        app.logger.info(f"Number of files received: {len(files)}")

        job_title = request.form.get('jobTitle')
        furniture_style = request.form.get('furnitureStyle')
        total_price = request.form.get('totalPrice')

        app.logger.info(f"Job Title: {job_title}")
        app.logger.info(f"Furniture Style: {furniture_style}")
        app.logger.info(f"Total Price: {total_price}")
        
        if not job_title:
            app.logger.error("Missing job title")
            return jsonify({'error': 'Missing job title'}), 400
        if not furniture_style:
            app.logger.error("Missing furniture style")
            return jsonify({'error': 'Missing furniture style'}), 400
        if not total_price:
            app.logger.error("Missing total price")
            return jsonify({'error': 'Missing total price'}), 400

        try:
            total_price = float(total_price)
        except ValueError:
            app.logger.error(f"Invalid total price: {total_price}")
            return jsonify({'error': 'Invalid total price'}), 400

        new_project = Project(
            name=job_title,
            description=f"Virtual Renovation - {furniture_style}",
            user_id=current_user.id,
            status='entwurf',
            cost=total_price,
            project_type='virtual_renovation',
            furniture_style=furniture_style,
            created_at=datetime.utcnow()
        )

        db.session.add(new_project)
        db.session.flush()  # This will assign an ID to new_project
        app.logger.info(f"Created new draft project with ID: {new_project.id}")

        # Define the base upload folder
        base_upload_folder = '/var/www/auftrag.immoyes.com/upload'

        # Create a folder for the user based on their email and project ID
        user_folder = os.path.join(base_upload_folder, current_user.email)
        project_folder = os.path.join(user_folder, str(new_project.id))
        os.makedirs(project_folder, exist_ok=True)
        app.logger.info(f"Created project folder: {project_folder}")

        image_links = []
        for i, file in enumerate(files):
            if file and allowed_file(file.filename):
                filename = secure_filename(file.filename)
                file_path = os.path.join(project_folder, filename)
                file.save(file_path)
                app.logger.info(f"Saved file: {file_path}")
                image_links.append(filename)  # Only append the filename, not the full path

                room_type = request.form.get(f'roomTypes[{i}]', '')
                notes = request.form.get(f'notes[{i}]', '')

                new_image = Image(
                    project_id=new_project.id,
                    file_path=filename,  # Only store the filename, not the full path
                    room_type=room_type,
                    notes=notes
                )
                db.session.add(new_image)
                app.logger.info(f"Added new image to database: {filename}")
            else:
                app.logger.error(f"Invalid file: {file.filename}")
                return jsonify({'error': f'Invalid file: {file.filename}'}), 400

        new_project.image_links = json.dumps(image_links)
        app.logger.info(f"Added image links to project: {image_links}")

        db.session.commit()
        app.logger.info("Committed changes to database")

        app.logger.info("Virtual renovation job saved as draft successfully")
        return jsonify({
            'message': 'Virtual renovation job saved as draft successfully',
            'project_id': new_project.id
        }), 201
    except Exception as e:
        app.logger.error(f"Error in save_virtual_renovation_draft: {str(e)}")
        app.logger.exception("Full traceback:")
        db.session.rollback()
        return jsonify({'error': str(e)}), 500

@app.route('/api/virtual-renovation', methods=['POST'])
@token_required
def submit_virtual_renovation(current_user):
    try:
        # Enhanced logging
        app.logger.info("Request method: %s", request.method)
        app.logger.info("Request form: %s", request.form)
        app.logger.info("Request files: %s", request.files)
        
        # Log each file separately
        for key, file in request.files.items():
            app.logger.info("File: %s, Filename: %s", key, file.filename)
        
        # Log other form data
        for key, value in request.form.items():
            app.logger.info("Form data: %s = %s", key, value)

        if 'files' not in request.files:
            app.logger.error("No file part in the request")
            return jsonify({'error': 'No file part'}), 400
        
        files = request.files.getlist('files')
        app.logger.info(f"Number of files received: {len(files)}")

        job_title = request.form.get('jobTitle')
        furniture_style = request.form.get('furnitureStyle')
        total_price = request.form.get('totalPrice')

        app.logger.info(f"Job Title: {job_title}")
        app.logger.info(f"Furniture Style: {furniture_style}")
        app.logger.info(f"Total Price: {total_price}")
        
        if not job_title or not furniture_style or not total_price:
            missing = []
            if not job_title: missing.append('job title')
            if not furniture_style: missing.append('furniture style')
            if not total_price: missing.append('total price')
            app.logger.error(f"Missing: {', '.join(missing)}")
            return jsonify({'error': f"Missing: {', '.join(missing)}"}), 400

        try:
            total_price = float(total_price)
        except ValueError:
            app.logger.error(f"Invalid total price: {total_price}")
            return jsonify({'error': 'Invalid total price'}), 400

        if current_user.credits < total_price:
            app.logger.error(f"Insufficient credits. User has {current_user.credits}, needed {total_price}")
            return jsonify({'error': 'Insufficient credits'}), 400

        new_project = Project(
            name=job_title,
            description=f"Virtual Renovation - {furniture_style}",
            user_id=current_user.id,
            status='In Bearbeitung',
            cost=total_price,
            project_type='virtual_renovation',
            furniture_style=furniture_style,
            created_at=datetime.utcnow()
        )

        db.session.add(new_project)
        db.session.flush()  # This will assign an ID to new_project

        # Create a folder for the user based on their email and project ID
        user_folder = os.path.join(app.config['UPLOAD_FOLDER'], current_user.email)
        project_folder = os.path.join(user_folder, str(new_project.id))
        os.makedirs(project_folder, exist_ok=True)

        image_links = []
        room_details = []
        for i, file in enumerate(files):
            if file and allowed_file(file.filename):
                filename = secure_filename(file.filename)
                file_path = os.path.join(project_folder, filename)
                file.save(file_path)
                image_links.append(filename)


                room_type = request.form.get(f'roomTypes[{i}]', '')
                notes = request.form.get(f'notes[{i}]', '')

                room_details.append({
                    'room_type': room_type,
                    'notes': notes
                })

                new_image = Image(
                    project_id=new_project.id,
                    file_path=filename,
                    room_type=room_type,
                    notes=notes
                )
                db.session.add(new_image)
            else:
                app.logger.error(f"Invalid file: {file.filename}")
                return jsonify({'error': f'Invalid file: {file.filename}'}), 400

        new_project.image_links = json.dumps(image_links)
        
        current_user.credits -= total_price

        db.session.commit()
        user_identifier = current_user.email

        # Send email to admin
        admin_subject = f"New Project: {len(files)} pictures, ${total_price:.2f}, {user_identifier}"
        admin_body = f"""
        New virtual renovation project submitted:

        Project ID: {new_project.id}
        Job Title: {job_title}
        Customer: {user_identifier}
        Furniture Style: {furniture_style}
        Total Price: ${total_price:.2f}
        Number of Images: {len(files)}

        Room Details:
        """

        for i, room in enumerate(room_details):
            admin_body += f"\n- Room {i+1}: {room['room_type']}\n  Notes: {room['notes']}"

        admin_msg = Message(admin_subject,
                      sender=app.config['MAIL_USERNAME'],
                      recipients=['jansen.tobias@gmail.com'])
        admin_msg.body = admin_body
        mail.send(admin_msg)

        # Send email to customer
        customer_subject = "Neues Projekt erstellt"
        customer_html = f"""
        <!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Neues Projekt erstellt</title>
            <style>
                body {{ font-family: Arial, sans-serif; line-height: 1.6; color: #333; }}
                .container {{ max-width: 600px; margin: 0 auto; padding: 20px; }}
                .logo {{ text-align: center; margin-bottom: 20px; }}
                .button {{
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: #ffffff !important;
                    text-decoration: none;
                    border-radius: 5px;
                    font-weight: bold;
                }}
                .button:hover {{ background-color: #0056b3; }}
            </style>
        </head>
        <body>
            <div class="container">
                <div class="logo">
                    <img src="cid:logo" alt="ImmoYes Logo" style="max-width: 200px;">
                </div>
                <h1>Vielen Dank für Ihr neues Projekt!</h1>
                <p>Sehr geehrte(r) Kunde(in),</p>
                <p>Wir freuen uns, Ihnen mitteilen zu können, dass Ihr neues Projekt erfolgreich erstellt wurde.</p>
                <p>Sie können Ihr Projekt jederzeit über den folgenden Link einsehen:</p>
                <p style="text-align: center;">
                    <a href="http://auftrag.immoyes.com/index.php?page=project-details&id={new_project.id}" class="button" style="color: #ffffff !important;">Projekt ansehen</a>
                </p>
                <p>Wir werden Sie über den Fortschritt Ihres Projekts auf dem Laufenden halten. Bei Fragen stehen wir Ihnen gerne zur Verfügung.</p>
                <p>Mit freundlichen Grüßen,<br>Ihr ImmoYes Team</p>
            </div>
        </body>
        </html>
        """

        customer_msg = Message(customer_subject,
                               sender=app.config['MAIL_USERNAME'],
                               recipients=[current_user.email],
                               bcc=['jansen.tobias@gmail.com'])
        customer_msg.html = customer_html

        # Attach logo image
        with app.open_resource("static/logo.png", "rb") as logo:
            customer_msg.attach("logo.png", "image/png", logo.read(), "inline", headers={'Content-ID': '<logo>'})

        mail.send(customer_msg)

        app.logger.info("Virtual renovation job submitted successfully and emails sent")
        return jsonify({
            'message': 'Virtual renovation job submitted successfully',
            'project_id': new_project.id,
            'remaining_credits': current_user.credits
        }), 201
    except Exception as e:
        app.logger.error(f"Error in submit_virtual_renovation: {str(e)}")
        app.logger.exception("Full traceback:")
        db.session.rollback()
        return jsonify({'error': str(e)}), 500

@app.route('/profile', methods=['GET'])
@token_required
def get_profile(current_user):
    user_data = {'username': current_user.username, 'email': current_user.email, 'credits': current_user.credits}
    return jsonify(user_data)

@app.route('/profile', methods=['PUT'])
@token_required
def update_profile(current_user):
    data = request.get_json()
    current_user.username = data.get('username', current_user.username)
    current_user.email = data.get('email', current_user.email)
    db.session.commit()
    return jsonify({'message': 'Profile updated successfully'})

@app.route('/extended-profile', methods=['GET'])
@token_required
def get_extended_profile(current_user):
    user_data = {
        'username': current_user.username,
        'email': current_user.email,
        'credits': current_user.credits,
        'vorname': current_user.vorname,
        'nachname': current_user.nachname,
        'adresse': current_user.adresse,
        'zip': current_user.zip,
        'city': current_user.city
    }
    return jsonify(user_data)

@app.route('/update-profile', methods=['POST'])
@token_required
def update_profile1(current_user):
    data = request.json
    
    # Update fields if they're provided in the request
    if 'vorname' in data:
        current_user.vorname = data['vorname']
    if 'nachname' in data:
        current_user.nachname = data['nachname']
    if 'adresse' in data:
        current_user.adresse = data['adresse']
    if 'zip' in data:
        current_user.zip = data['zip']
    if 'city' in data:
        current_user.city = data['city']
    
    db.session.commit()
    
    return jsonify({'message': 'Profile updated successfully'}), 200


@app.route('/projects', methods=['POST'])
@token_required
def create_project(current_user):
    data = request.get_json()
    new_project = Project(
        name=data['name'],
        description=data['description'],
        user_id=current_user.id,
        created_at=datetime.datetime.utcnow()
    )
    db.session.add(new_project)
    db.session.commit()
    return jsonify({
        'message': 'New project created!',
        'project': {
            'id': new_project.id,
            'name': new_project.name,
            'description': new_project.description,
            'status': new_project.status,
            'created_at': new_project.created_at.isoformat()
        }
    }), 201

@app.route('/api/jobs', methods=['GET'])
@token_required
def get_jobs(current_user):
    jobs = [
        {
            "title": "Virtuelles Homestaging",
            "price": 69.00,
            "beforeImage": "https://auftrag.immoyes.com/static/bilder/VHB.webp",
            "afterImage": "https://auftrag.immoyes.com/static/bilder/VHA.webp"
        },
        {
            "title": "Virtuelle Renovierung",
            "price": 99.00,
            "beforeImage": "https://auftrag.immoyes.com/static/bilder/VRB.jpg",
            "afterImage": "https://auftrag.immoyes.com/static/bilder/VRA.webp"
        },
        {
            "title": "3D Grundriss",
            "price": 69.00,
            "beforeImage": "https://auftrag.immoyes.com/static/bilder/GRA.jpeg",
            "afterImage": "https://auftrag.immoyes.com/static/bilder/GRB.webp"
        }
    ]
    return jsonify(jobs)

@app.route('/projects/<int:project_id>', methods=['GET'])
@token_required
def get_project(current_user, project_id):
    project = Project.query.filter_by(id=project_id, user_id=current_user.id).first()
    if not project:
        return jsonify({'message': 'Project not found'}), 404
    return jsonify({
        'id': project.id,
        'name': project.name,
        'description': project.description,
        'status': project.status,
        'created_at': project.created_at.isoformat() if project.created_at else None
    })

@app.route('/credits/add', methods=['POST'])
@token_required
def add_credits(current_user):
    data = request.get_json()
    amount = data.get('amount', 0)
    current_user.credits += amount
    db.session.commit()
    return jsonify({'message': f'Added {amount} credits', 'new_balance': current_user.credits})

@app.route('/credits/use', methods=['POST'])
@token_required
def use_credits(current_user):
    data = request.get_json()
    amount = data.get('amount', 0)
    if current_user.credits < amount:
        return jsonify({'message': 'Insufficient credits'}), 400
    current_user.credits -= amount
    db.session.commit()
    return jsonify({'message': f'Used {amount} credits', 'new_balance': current_user.credits})

@app.route('/admin/users', methods=['GET'])
@token_required
def get_all_users(current_user):
    if not current_user.is_admin:
        return jsonify({'message': 'Admin access required'}), 403
    users = User.query.all()
    return jsonify([{'id': u.id, 'username': u.username, 'email': u.email, 'is_admin': u.is_admin} for u in users])

@app.route('/projects', methods=['GET'])
@token_required
def get_projects(current_user):
    projects = Project.query.filter_by(user_id=current_user.id).all()
    return jsonify([{
        'id': project.id,
        'name': project.name,
        'status': project.status,
        'created_at': project.created_at.isoformat() if project.created_at else None
    } for project in projects])

# In the get_projects_paginated function, add this logging:
def get_projects_paginated(current_user, page, per_page):
    projects = Project.query.filter_by(user_id=current_user.id)\
                            .order_by(Project.created_at.desc())\
                            .paginate(page=page, per_page=per_page, error_out=False)
    
    items = []
    for project in projects.items:
        image_links = json.loads(project.image_links) if project.image_links else []
        image_count = len(image_links)
        logging.debug(f"Project ID: {project.id}, Image Links: {image_links}, Image Count: {image_count}")
        items.append({
            'id': project.id,
            'name': project.name,
            'status': project.status,
            'created_at': project.created_at.isoformat() if project.created_at else None,
            'cost': project.cost,
            'furniture_style': project.furniture_style,
            'project_type': project.project_type,
            'image_links': image_links,
            'final_image_links': json.loads(project.final_image_links) if project.final_image_links else [],
            'image_count': image_count  # Explicitly include the image count
        })
    
    return {
        'items': items,
        'total': projects.total,
        'pages': projects.pages,
        'current_page': projects.page
    }

@app.route('/batch', methods=['POST'])
@token_required
def batch_request(current_user):
    batch_data = request.json
    results = {}

    # Collect all project IDs needed for project details
    project_ids = [query['project_id'] for key, query in batch_data.items() if query['endpoint'] == 'project_details']

    # Fetch all needed projects in one query
    projects_dict = {project.id: project for project in Project.query.filter(Project.id.in_(project_ids), Project.user_id == current_user.id)}

    for key, query in batch_data.items():
        if query['endpoint'] == 'projects':
            page = query.get('page', 1)
            per_page = query.get('per_page', 9)
            results[key] = get_projects_paginated(current_user, page, per_page)
        elif query['endpoint'] == 'project_details':
            project_id = query['project_id']
            project = projects_dict.get(project_id)
            if project:
                results[key] = get_project_details(current_user, project_id)
            else:
                results[key] = None

    return jsonify(results)

def get_project_details(current_user, project_id):
    project = Project.query.filter_by(id=project_id, user_id=current_user.id).first()
    if project:
        image_links = json.loads(project.image_links) if project.image_links else []
        final_image_links = json.loads(project.final_image_links) if project.final_image_links else []

        revisions = Revision.query.filter_by(project_id=project.id).order_by(Revision.created_at.desc()).all()
        revision_data = [{
            'id': revision.id,
            'revision_text': revision.revision_text,
            'image_id': revision.image_id,
            'created_at': revision.created_at.isoformat() if revision.created_at else None
        } for revision in revisions]

        return {
            'id': project.id,
            'name': project.name,
            'description': project.description,
            'status': project.status,
            'cost': project.cost,
            'furniture_style': project.furniture_style,
            'project_type': project.project_type,
            'created_at': project.created_at.isoformat() if project.created_at else None,
            'image_links': image_links,
            'final_image_links': final_image_links,
            'revisions': revision_data,
            'image_count': len(image_links)
        }
    return None

@app.route('/user', methods=['GET'])
@token_required
def get_user(current_user):
    user_data = {
        'username': current_user.username,
        'email': current_user.email,
        'credits': current_user.credits
    }
    return jsonify(user_data)

@app.route('/api/virtual-staging', methods=['POST'])
@token_required
def submit_virtual_staging(current_user):
    try:
        app.logger.info(f"Received virtual staging request. Form data: {request.form}")
        app.logger.info(f"Files received: {request.files}")

        if 'files' not in request.files:
            app.logger.error("No file part in the request")
            return jsonify({'error': 'No file part'}), 400
        
        files = request.files.getlist('files')
        app.logger.info(f"Number of files received: {len(files)}")

        job_title = request.form.get('jobTitle')
        furniture_style = request.form.get('furnitureStyle')
        total_price = request.form.get('totalPrice')

        app.logger.info(f"Job Title: {job_title}")
        app.logger.info(f"Furniture Style: {furniture_style}")
        app.logger.info(f"Total Price: {total_price}")
        
        if not job_title or not furniture_style or not total_price:
            missing = []
            if not job_title: missing.append('job title')
            if not furniture_style: missing.append('furniture style')
            if not total_price: missing.append('total price')
            app.logger.error(f"Missing: {', '.join(missing)}")
            return jsonify({'error': f"Missing: {', '.join(missing)}"}), 400

        try:
            total_price = float(total_price)
        except ValueError:
            app.logger.error(f"Invalid total price: {total_price}")
            return jsonify({'error': 'Invalid total price'}), 400

        if current_user.credits < total_price:
            app.logger.error(f"Insufficient credits. User has {current_user.credits}, needed {total_price}")
            return jsonify({'error': 'Insufficient credits'}), 400

        new_project = Project(
            name=job_title,
            description=f"Virtual Staging - {furniture_style}",
            user_id=current_user.id,
            status='In Bearbeitung',
            cost=total_price,
            furniture_style=furniture_style,
            created_at=datetime.utcnow()
        )

        db.session.add(new_project)
        db.session.flush()  # This will assign an ID to new_project

        # Create a folder structure: user_email/project_id
        user_folder = os.path.join(app.config['UPLOAD_FOLDER'], current_user.email)
        project_folder = os.path.join(user_folder, str(new_project.id))
        os.makedirs(project_folder, exist_ok=True)

        image_links = []
        room_details = []
        for i, file in enumerate(files):
            if file and allowed_file(file.filename):
                filename = secure_filename(file.filename)
                file_path = os.path.join(project_folder, filename)
                file.save(file_path)
                image_links.append(filename)  # Store only the filename

                room_type = request.form.get(f'roomTypes[{i}]', '')
                notes = request.form.get(f'notes[{i}]', '')

                room_details.append({
                    'room_type': room_type,
                    'notes': notes
                })

                new_image = Image(
                    project_id=new_project.id,
                    file_path=filename,  # Store only the filename
                    room_type=room_type,
                    notes=notes
                )
                db.session.add(new_image)
            else:
                app.logger.error(f"Invalid file: {file.filename}")
                return jsonify({'error': f'Invalid file: {file.filename}'}), 400

        new_project.image_links = json.dumps(image_links)
        
        current_user.credits -= total_price

        db.session.commit()

        user_identifier = current_user.email

        # Send email to admin
        admin_subject = f"New Virtual Staging Project: {len(files)} pictures, ${total_price:.2f}, {user_identifier}"
        admin_body = f"""
        New virtual staging project submitted:

        Project ID: {new_project.id}
        Job Title: {job_title}
        Customer: {user_identifier}
        Furniture Style: {furniture_style}
        Total Price: ${total_price:.2f}
        Number of Images: {len(files)}

        Room Details:
        """

        for i, room in enumerate(room_details):
            admin_body += f"\n- Room {i+1}: {room['room_type']}\n  Notes: {room['notes']}"

        admin_msg = Message(admin_subject,
                      sender=app.config['MAIL_USERNAME'],
                      recipients=['jansen.tobias@gmail.com'])
        admin_msg.body = admin_body
        mail.send(admin_msg)

        # Send email to customer
        customer_subject = "Neues Virtual Staging Projekt erstellt"
        customer_html = f"""
        <!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Neues Virtual Staging Projekt erstellt</title>
            <style>
                body {{ font-family: Arial, sans-serif; line-height: 1.6; color: #333; }}
                .container {{ max-width: 600px; margin: 0 auto; padding: 20px; }}
                .logo {{ text-align: center; margin-bottom: 20px; }}
                .button {{
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: #ffffff !important;
                    text-decoration: none;
                    border-radius: 5px;
                    font-weight: bold;
                }}
                .button:hover {{ background-color: #0056b3; }}
            </style>
        </head>
        <body>
            <div class="container">
                <div class="logo">
                    <img src="cid:logo" alt="ImmoYes Logo" style="max-width: 200px;">
                </div>
                <h1>Vielen Dank für Ihr neues Virtual Staging Projekt!</h1>
                <p>Sehr geehrte(r) Kunde(in),</p>
                <p>Wir freuen uns, Ihnen mitteilen zu können, dass Ihr neues Virtual Staging Projekt erfolgreich erstellt wurde.</p>
                <p>Sie können Ihr Projekt jederzeit über den folgenden Link einsehen:</p>
                <p style="text-align: center;">
                    <a href="http://auftrag.immoyes.com/index.php?page=project-details&id={new_project.id}" class="button" style="color: #ffffff !important;">Projekt ansehen</a>
                </p>
                <p>Wir werden Sie über den Fortschritt Ihres Projekts auf dem Laufenden halten. Bei Fragen stehen wir Ihnen gerne zur Verfügung.</p>
                <p>Mit freundlichen Grüßen,<br>Ihr ImmoYes Team</p>
            </div>
        </body>
        </html>
        """

        customer_msg = Message(customer_subject,
                               sender=app.config['MAIL_USERNAME'],
                               recipients=[current_user.email],
                               bcc=['jansen.tobias@gmail.com'])
        customer_msg.html = customer_html

        # Attach logo image
        with app.open_resource("static/logo.png", "rb") as logo:
            customer_msg.attach("logo.png", "image/png", logo.read(), "inline", headers={'Content-ID': '<logo>'})

        mail.send(customer_msg)

        app.logger.info("Virtual staging job submitted successfully and emails sent")
        return jsonify({
            'message': 'Virtual staging job submitted successfully',
            'project_id': new_project.id,
            'remaining_credits': current_user.credits
        }), 201
    except Exception as e:
        app.logger.error(f"Error in submit_virtual_staging: {str(e)}")
        app.logger.exception("Full traceback:")
        db.session.rollback()
        return jsonify({'error': str(e)}), 500
    
@app.route('/project/<int:project_id>', methods=['GET'])
@token_required
def get_project_details(current_user, project_id):
    project = Project.query.filter_by(id=project_id, user_id=current_user.id).first()
    if not project:
        return jsonify({'message': 'Project not found'}), 404
    
    images = Image.query.filter_by(project_id=project.id).all()
    image_data = [{
        'id': image.id,
        'file_path': image.file_path,
        'room_type': image.room_type,
        'notes': image.notes
    } for image in images]
    
    final_image_links = json.loads(project.final_image_links) if project.final_image_links else []
    
    revisions = Revision.query.filter_by(project_id=project.id).order_by(Revision.created_at.desc()).all()
    revision_data = [{
        'id': revision.id,
        'revision_text': revision.revision_text,
        'image_id': revision.image_id,
        'created_at': revision.created_at.isoformat() if revision.created_at else None
    } for revision in revisions]
    
    return jsonify({
        'id': project.id,
        'name': project.name,
        'description': project.description,
        'status': project.status,
        'cost': project.cost,
        'furniture_style': project.furniture_style,
        'images': image_data,
        'final_images': final_image_links,
        'revisions': revision_data,
        'created_at': project.created_at.isoformat() if project.created_at else None,
    }), 200

@app.route('/project/<int:project_id>/image/<path:filename>', methods=['GET'])
@token_required
def get_project_image(current_user, project_id, filename):
    project = Project.query.filter_by(id=project_id, user_id=current_user.id).first()
    if not project:
        return jsonify({'message': 'Project not found'}), 404
    
    # Convert Windows-style path to URL-friendly format
    filename = filename.replace('\\', '/')
    
    image_path = os.path.join(app.config['UPLOAD_FOLDER'], filename)
    if not os.path.exists(image_path):
        return jsonify({'message': 'Image not found'}), 404
    
    return send_file(image_path, mimetype='image/jpeg')





@app.route('/project/<int:project_id>/revision', methods=['POST'])
@token_required
def request_revision(current_user, project_id):
    project = Project.query.filter_by(id=project_id, user_id=current_user.id).first()
    if not project:
        return jsonify({'message': 'Project not found'}), 404
    
    if project.status.lower() == 'in progress':
        return jsonify({'message': 'Cannot request revision for a project in progress'}), 400
    
    data = request.get_json()
    current_app.logger.info(f"Received revision data: {data}")
    
    revisions = data.get('revisions', [])
    
    if not revisions:
        return jsonify({'message': 'No revision data provided'}), 400
    
    try:
        revision_details = []
        for revision in revisions:
            revision_text = revision.get('revision_text')
            image_id = revision.get('image_id')
            
            if not revision_text or not isinstance(revision_text, str):
                return jsonify({'message': 'Invalid revision text'}), 400
            
            if image_id is None:
                return jsonify({'message': 'Missing image_id for revision'}), 400
            
            new_revision = Revision(
                project_id=project.id,
                image_id=image_id,
                revision_text=revision_text
            )
            db.session.add(new_revision)
            revision_details.append(f"<li>Image ID: {image_id}, Revision: {revision_text}</li>")
        
        project.status = 'Änderung gewünscht'
        db.session.commit()
        
        # Prepare and send email
        subject = f"New Revision Request for Project {project_id}"
        body = f"""
        <html>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                <img src="cid:logo" alt="Company Logo" style="display: block; margin-bottom: 20px; max-width: 200px;">
                <h2 style="color: #4a4a4a;">New Revision Request for Project {project_id}</h2>
                <p>A new revision request has been submitted for Project {project_id}.</p>
                <h3 style="color: #4a4a4a;">Revision Details:</h3>
                <ul>
                    {"".join(revision_details)}
                </ul>
                <p>
                    <a href="http://auftrag.immoyes.com/project_details.php?id={project_id}" style="background-color: #4CAF50; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block;">View Project</a>
                </p>
                <p>Please review the revision request and take necessary action.</p>
            </div>
        </body>
        </html>
        """
        
        # Create a message object
        msg = Message(subject,
                      sender='ImmoYes<portal@immoyes.com>',
                      recipients=["jansen.tobias@gmail.com"])
        msg.html = body

        # Attach the logo
        with app.open_resource("static/logo.png", "rb") as logo:
            msg.attach("logo.png", "image/png", logo.read(), "inline", headers={'Content-ID': '<logo>'})

        # Send the email
        mail.send(msg)
        
        return jsonify({'message': 'Revision request submitted successfully'}), 200
    except Exception as e:
        db.session.rollback()
        current_app.logger.error(f"Error in revision request: {str(e)}")
        return jsonify({'message': f'An error occurred: {str(e)}'}), 500

@app.route('/admin/projects/in-progress', methods=['GET'])
@token_required
def get_in_progress_projects(current_user):
    if not current_user.is_admin:
        return jsonify({'message': 'Admin access required'}), 403
    
    projects = Project.query.filter_by(status='in bearbeitung').all()
    project_list = []
    for project in projects:
        user = User.query.get(project.user_id)
        images = Image.query.filter_by(project_id=project.id).all()
        image_list = [{'file_path': img.file_path, 'room_type': img.room_type, 'notes': img.notes} for img in images]
        
        project_list.append({
            'id': project.id,
            'name': project.name,
            'description': project.description,
            'status': project.status,
            'cost': project.cost,
            'furniture_style': project.furniture_style,
            'created_at': project.created_at.isoformat() if project.created_at else None,
            'username': user.username if user else 'Unknown',
            'images': image_list
        })
    
    return jsonify(project_list)



@app.route('/admin/project/<int:project_id>', methods=['GET'])
@token_required
def get_admin_project_details(current_user, project_id):
    if not current_user.is_admin:
        return jsonify({'message': 'Admin access required'}), 403
    
    project = Project.query.get(project_id)
    if not project:
        return jsonify({'message': 'Project not found'}), 404
    
    user = User.query.get(project.user_id)
    images = Image.query.filter_by(project_id=project.id).all()
    image_data = [{
        'id': image.id,
        'file_path': image.file_path,
        'room_type': image.room_type,
        'notes': image.notes
    } for image in images]
    
    final_image_links = json.loads(project.final_image_links) if project.final_image_links else []
    
    revisions = Revision.query.filter_by(project_id=project.id).order_by(Revision.created_at.desc()).all()
    revision_data = [{
        'id': revision.id,
        'revision_text': revision.revision_text,
        'image_id': revision.image_id,
        'created_at': revision.created_at.isoformat() if revision.created_at else None
    } for revision in revisions]
    
    return jsonify({
        'id': project.id,
        'name': project.name,
        'description': project.description,
        'status': project.status,
        'cost': project.cost,
        'furniture_style': project.furniture_style,
        'created_at': project.created_at.isoformat() if project.created_at else None,
        'username': user.username if user else 'Unknown',
        'email': user.email if user else 'Unknown',
        'images': image_data,
        'final_images': final_image_links,
        'revisions': revision_data
    }), 200


@app.route('/api/virtual-staging/draft', methods=['POST'])
@token_required
def save_virtual_staging_draft(current_user):
    try:
        app.logger.info(f"Received virtual staging draft request. Form data: {request.form}")
        app.logger.info(f"Files received: {request.files}")

        if 'files' not in request.files:
            app.logger.error("No file part in the request")
            return jsonify({'error': 'No file part'}), 400
        
        files = request.files.getlist('files')
        app.logger.info(f"Number of files received: {len(files)}")

        job_title = request.form.get('jobTitle')
        furniture_style = request.form.get('furnitureStyle')
        total_price = request.form.get('totalPrice')

        app.logger.info(f"Job Title: {job_title}")
        app.logger.info(f"Furniture Style: {furniture_style}")
        app.logger.info(f"Total Price: {total_price}")
        
        if not job_title:
            app.logger.error("Missing job title")
            return jsonify({'error': 'Missing job title'}), 400
        if not furniture_style:
            app.logger.error("Missing furniture style")
            return jsonify({'error': 'Missing furniture style'}), 400
        if not total_price:
            app.logger.error("Missing total price")
            return jsonify({'error': 'Missing total price'}), 400

        try:
            total_price = float(total_price)
        except ValueError:
            app.logger.error(f"Invalid total price: {total_price}")
            return jsonify({'error': 'Invalid total price'}), 400

        new_project = Project(
            name=job_title,
            description=f"Virtual Staging - {furniture_style}",
            user_id=current_user.id,
            status='entwurf',
            cost=total_price,
            project_type='virtual_staging',
            furniture_style=furniture_style,
            created_at=datetime.utcnow()
        )

        db.session.add(new_project)
        db.session.flush()  # This will assign an ID to new_project
        app.logger.info(f"Created new draft project with ID: {new_project.id}")

        # Define the base upload folder
        base_upload_folder = '/var/www/auftrag.immoyes.com/upload'

        # Create a folder for the user based on their email and project ID
        user_folder = os.path.join(base_upload_folder, current_user.email)
        project_folder = os.path.join(user_folder, str(new_project.id))
        os.makedirs(project_folder, exist_ok=True)
        app.logger.info(f"Created project folder: {project_folder}")

        image_links = []
        for i, file in enumerate(files):
            if file and allowed_file(file.filename):
                filename = secure_filename(file.filename)
                file_path = os.path.join(project_folder, filename)
                file.save(file_path)
                app.logger.info(f"Saved file: {file_path}")
                image_links.append(filename)  # Only append the filename, not the full path

                room_type = request.form.get(f'roomTypes[{i}]', '')
                notes = request.form.get(f'notes[{i}]', '')

                new_image = Image(
                    project_id=new_project.id,
                    file_path=filename,  # Only store the filename, not the full path
                    room_type=room_type,
                    notes=notes
                )
                db.session.add(new_image)
                app.logger.info(f"Added new image to database: {filename}")
            else:
                app.logger.error(f"Invalid file: {file.filename}")
                return jsonify({'error': f'Invalid file: {file.filename}'}), 400

        new_project.image_links = json.dumps(image_links)
        app.logger.info(f"Added image links to project: {image_links}")

        db.session.commit()
        app.logger.info("Committed changes to database")

        app.logger.info("Virtual staging job saved as draft successfully")
        return jsonify({
            'message': 'Virtual staging job saved as draft successfully',
            'project_id': new_project.id
        }), 201
    except Exception as e:
        app.logger.error(f"Error in save_virtual_staging_draft: {str(e)}")
        app.logger.exception("Full traceback:")
        db.session.rollback()
        return jsonify({'error': str(e)}), 500
    

@app.route('/api/user-credits', methods=['GET'])
@token_required
def get_user_credits(current_user):
    return jsonify({'credits': current_user.credits})   



def send_email(subject, body, recipients):
    try:
        msg = Message(subject,
                      sender='ImmoYes<portal@immoyes.com>',
                      recipients=recipients)
        msg.body = body
        mail.send(msg)
        current_app.logger.info(f"Email sent successfully: {subject}")
    except Exception as e:
        current_app.logger.error(f"Error sending email: {str(e)}")
        current_app.logger.error(f"Error details: {e.__class__.__name__}")
        current_app.logger.error(f"Mail configuration: {current_app.config['MAIL_SERVER']}, {current_app.config['MAIL_PORT']}, SSL: {current_app.config['MAIL_USE_SSL']}, TLS: {current_app.config['MAIL_USE_TLS']}")

@app.route('/api/purchase-credits', methods=['POST'])
@token_required
def purchase_credits(current_user):
    data = request.get_json()
    credits_to_add = data.get('credits')
    transaction_id = data.get('transactionId')
    amount = data.get('amount')
    payment_method = data.get('method')
    
    if not all([credits_to_add, transaction_id, amount, payment_method]):
        return jsonify({'error': 'Missing required fields'}), 400
    
    try:
        credits_to_add = float(credits_to_add)
        amount = float(amount)
        if credits_to_add <= 0 or amount <= 0:
            return jsonify({'error': 'Invalid credit amount or transaction amount'}), 400
        
        # Create a new transaction record
        new_transaction = Transaction(
            user_id=current_user.id,
            transaction_id=transaction_id,
            credits=credits_to_add,
            amount=amount
        )
        db.session.add(new_transaction)
        
        if payment_method == 'paypal':
            # Process PayPal payment (assuming it's already verified)
            current_user.credits += credits_to_add
            db.session.commit()
            return jsonify({
                'status': 'success',
                'message': 'Credits added successfully',
                'new_balance': current_user.credits
            }), 200
        
        elif payment_method == 'bank_transfer':
            # For bank transfer, we don't add credits immediately
            db.session.commit()  # Commit the transaction record
            
            # Generate a unique reference number
            reference = f"IMY-{transaction_id}"
            
            # Prepare and send email
            subject = f"New Bank Transfer Request: {reference}"
            body = f"""
            New bank transfer request details:
            
            Reference: {reference}
            User ID: {current_user.id}
            Amount: {amount} €
            Credits: {credits_to_add}
            Transaction ID: {transaction_id}
            
            Please verify the payment and update the user's credit balance manually.
            """
            
            send_email(subject, body, ["jansen.tobias@gmail.com"])
            
            return jsonify({
                'status': 'success',
                'message': 'Bank transfer request registered successfully',
                'reference': reference
            }), 200
        
        else:
            return jsonify({'error': 'Invalid payment method'}), 400
    
    except ValueError:
        return jsonify({'error': 'Invalid credit amount or transaction amount'}), 400
    except Exception as e:
        db.session.rollback()
        current_app.logger.error(f"Error processing purchase: {e}")
        return jsonify({'error': 'An error occurred while processing your request'}), 500

@app.route('/api/transactions', methods=['GET'])
@token_required
def get_transactions(current_user):
    transactions = Transaction.query.filter_by(user_id=current_user.id).order_by(Transaction.timestamp.desc()).all()
    
    transactions_data = []
    for transaction in transactions:
        transactions_data.append({
            'transaction_id': transaction.transaction_id,
            'timestamp': transaction.timestamp.isoformat(),
            'credits': transaction.credits,
            'amount': transaction.amount
        })
    
    return jsonify(transactions_data)    







@app.route('/test-email')
def test_email():
    app.logger.info("Starting test email function")
    try:
        # Log email configuration
        app.logger.info(f"Mail Server: {current_app.config['MAIL_SERVER']}")
        app.logger.info(f"Mail Port: {current_app.config['MAIL_PORT']}")
        app.logger.info(f"Mail Use TLS: {current_app.config['MAIL_USE_TLS']}")
        app.logger.info(f"Mail Use SSL: {current_app.config['MAIL_USE_SSL']}")
        app.logger.info(f"Mail Username: {current_app.config['MAIL_USERNAME']}")
        
        # Create message
        app.logger.info("Creating email message")
        msg = Message("Test Email",
                      sender='ImmoYes<portal@immoyes.com>',

                      recipients=["jansen.tobias@gmail.com"])
        msg.body = "This is a test email from your Flask application."
        
        # Attempt to send email
        app.logger.info("Attempting to send email")
        mail.send(msg)
        
        app.logger.info("Email sent successfully")
        return "Test email sent successfully!"
    except SMTPException as e:
        app.logger.error(f"SMTP error occurred: {str(e)}")
        return f"SMTP error occurred: {str(e)}"
    except ConnectionRefusedError as e:
        app.logger.error(f"Connection refused: {str(e)}")
        return f"Connection refused: {str(e)}"
    except Exception as e:
        app.logger.error(f"Unexpected error sending test email: {str(e)}", exc_info=True)
        return f"Unexpected error sending test email: {str(e)}"
    finally:
        app.logger.info("Test email function completed")
    

def create_styled_paragraph(text, style, color="#333333", size=10, bold=False):
    return Paragraph(text, ParagraphStyle(
        name=f'Custom{hash(text)}',
        parent=style,
        textColor=HexColor(color),
        fontSize=size,
        fontName='Helvetica-Bold' if bold else 'Helvetica'
    ))

from flask import jsonify, send_file, current_app
from reportlab.lib.pagesizes import A4
from reportlab.lib.units import mm
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.enums import TA_RIGHT, TA_CENTER
from reportlab.lib import colors
from datetime import datetime
import io
import os
from functools import wraps

@app.route('/api/invoice/<transaction_id>', methods=['GET'])
@token_required
def download_invoice(current_user, transaction_id):
    transaction = Transaction.query.filter_by(user_id=current_user.id, transaction_id=transaction_id).first()
    if not transaction:
        return jsonify({'error': 'Transaktion nicht gefunden'}), 404

    buffer = io.BytesIO()
    doc = SimpleDocTemplate(buffer, pagesize=A4, topMargin=20*mm, bottomMargin=20*mm, leftMargin=20*mm, rightMargin=20*mm)
    
    def add_page_elements(canvas, doc):
        canvas.saveState()
        # Logo
        logo_path = os.path.join(current_app.root_path, 'static', 'logo.png')
        canvas.drawImage(logo_path, 20*mm, A4[1] - 40*mm, width=50*mm, height=25*mm, preserveAspectRatio=True)
        # Page number
        canvas.setFont("Roboto", 8)
        canvas.drawRightString(A4[0] - 20*mm, 10*mm, f"Seite {doc.page}")
        # Footer
        canvas.setFont("Roboto", 8)
        canvas.drawCentredString(A4[0]/2, 10*mm, "Vielen Dank für Ihr Vertrauen!")
        canvas.restoreState()

    elements = []
    styles = getSampleStyleSheet()
    styles.add(ParagraphStyle(name='Right', alignment=TA_RIGHT, fontName='Roboto'))
    styles.add(ParagraphStyle(name='Center', alignment=TA_CENTER, fontName='Roboto'))

    # Add space for logo
    elements.append(Spacer(1, 30*mm))

    # Customer and Company Information (side by side)
    customer_info = [
        Paragraph(f"{current_user.vorname} {current_user.nachname}", styles['Normal']),
        Paragraph(current_user.adresse, styles['Normal']),
        Paragraph(f"{current_user.zip} {current_user.city}", styles['Normal']),
    ]
    company_info = [
        Paragraph("Immo Yes", styles['Normal']),
        Paragraph("Tobias Jansen", styles['Normal']),
        Paragraph("Beamtenweg 2", styles['Normal']),
        Paragraph("52511 Geilenkirchen", styles['Normal']),
        Paragraph("E-Mail: info@immoyes.com", styles['Normal']),
       
    ]
    info_table = Table([[customer_info, company_info]], colWidths=[90*mm, 90*mm])
    info_table.setStyle(TableStyle([
        ('ALIGN', (0, 0), (0, -1), 'LEFT'),
        ('ALIGN', (1, 0), (1, -1), 'RIGHT'),
        ('VALIGN', (0, 0), (-1, -1), 'TOP'),
        ('FONTNAME', (0, 0), (-1, -1), 'Roboto'),
        ('FONTSIZE', (0, 0), (-1, -1), 8),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 1),
    ]))
    elements.append(info_table)
    elements.append(Spacer(1, 20*mm))

    # Invoice Title and Details
    elements.append(Paragraph("RECHNUNG", styles['Title']))
    elements.append(Spacer(1, 5*mm))
    invoice_info = [
        ["Rechnungsnummer:", transaction.transaction_id],
        ["Rechnungsdatum:", datetime.now().strftime("%d.%m.%Y")],
        ["Leistungsdatum:", datetime.now().strftime("%d.%m.%Y")],
        ["Kundennummer:", str(current_user.id)],
    ]
    invoice_table = Table(invoice_info, colWidths=[80*mm, 80*mm])
    invoice_table.setStyle(TableStyle([
        ('FONTNAME', (0, 0), (-1, -1), 'Roboto'),
        ('FONTSIZE', (0, 0), (-1, -1), 9),
        ('TOPPADDING', (0, 0), (-1, -1), 1),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 1),
        ('ALIGN', (0, 0), (-1, -1), 'LEFT'),
    ]))
    elements.append(invoice_table)
    elements.append(Spacer(1, 10*mm))

    # Transaction Details
    net_amount = transaction.amount / 1.19  # Assuming 19% VAT
    vat_amount = transaction.amount - net_amount
    data = [
        ["Pos", "Beschreibung", "Menge", "Einzelpreis", "Gesamtpreis"],
        ["1", "Guthaben", f"{transaction.credits:.2f}", f"€{net_amount/transaction.credits:.2f}", f"€{net_amount:.2f}"],
    ]
    table = Table(data, colWidths=[10*mm, 80*mm, 25*mm, 30*mm, 30*mm])
    table.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.lightgrey),
        ('TEXTCOLOR', (0, 0), (-1, 0), colors.black),
        ('ALIGN', (0, 0), (-1, -1), 'CENTER'),
        ('FONTNAME', (0, 0), (-1, 0), 'Roboto-Bold'),
        ('FONTSIZE', (0, 0), (-1, 0), 9),
        ('BOTTOMPADDING', (0, 0), (-1, 0), 12),
        ('BACKGROUND', (0, 1), (-1, -1), colors.white),
        ('TEXTCOLOR', (0, 1), (-1, -1), colors.black),
        ('ALIGN', (0, 1), (-1, -1), 'CENTER'),
        ('FONTNAME', (0, 1), (-1, -1), 'Roboto'),
        ('FONTSIZE', (0, 1), (-1, -1), 9),
        ('TOPPADDING', (0, 1), (-1, -1), 1),
        ('BOTTOMPADDING', (0, 1), (-1, -1), 1),
        ('GRID', (0, 0), (-1, -1), 0.5, colors.grey),
    ]))
    elements.append(table)
    elements.append(Spacer(1, 5*mm))

    # Totals
    totals = [
        ["Nettobetrag:", f"€{net_amount:.2f}"],
        ["19% MwSt.:", f"€{vat_amount:.2f}"],
        ["Gesamtbetrag:", f"€{transaction.amount:.2f}"],
    ]
    totals_table = Table(totals, colWidths=[150*mm, 25*mm])
    totals_table.setStyle(TableStyle([
        ('ALIGN', (0, 0), (0, -1), 'RIGHT'),
        ('ALIGN', (1, 0), (1, -1), 'RIGHT'),
        ('FONTNAME', (0, 0), (-1, -1), 'Roboto'),
        ('FONTSIZE', (0, 0), (-1, -1), 9),
        ('TOPPADDING', (0, 0), (-1, -1), 1),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 1),
        ('LINEABOVE', (0, -1), (-1, -1), 1, colors.black),
        ('FONTNAME', (0, -1), (-1, -1), 'Roboto-Bold'),
    ]))
    elements.append(totals_table)
    elements.append(Spacer(1, 10*mm))

    # Additional Information
    elements.append(Paragraph("Hinweis:", styles['Heading4']))
    elements.append(Paragraph("Diese Rechnung bezieht sich auf im Voraus bezahlte Guthaben. Eine separate Zahlung ist nicht erforderlich.", styles['Normal']))
    elements.append(Spacer(1, 5*mm))
    elements.append(Paragraph("Vielen Dank für Ihr Vertrauen in unsere Dienstleistungen!", styles['Normal']))

    
   

    # Build PDF
    doc.build(elements, onFirstPage=add_page_elements, onLaterPages=add_page_elements)
    buffer.seek(0)

    return send_file(
        buffer,
        as_attachment=True,
        download_name=f"Rechnung_{transaction.transaction_id}.pdf",
        mimetype='application/pdf'
    )
    







def send_styled_email(project, recipient):
    subject = "Immo Yes - Ihr Auftrag ist fertig!"
    body = f"""
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap');
            body {{
                font-family: 'Open Sans', Arial, sans-serif;
                line-height: 1.6;
                color: #333333;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }}
            .container {{
                max-width: 600px;
                margin: 20px auto;
                background-color: #ffffff;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 0 20px rgba(0,0,0,0.1);
            }}
            .header {{
                background-color: #4a90e2;
                padding: 30px 20px;
                text-align: center;
            }}
            .logo {{
                max-width: 200px;
                display: block;
                margin: 0 auto 20px;
            }}
            h1 {{
                color: #ffffff;
                margin: 0;
                font-size: 28px;
                font-weight: 600;
            }}
            .content {{
                padding: 40px 30px;
                text-align: center;
            }}
            p {{
                margin-bottom: 20px;
                font-size: 16px;
            }}
            .button {{
                display: inline-block;
                background-color: #2E7D32;
                color: #FFFFFF !important;
                padding: 12px 24px;
                text-decoration: none;
                border-radius: 5px;
                font-weight: 700;
                font-size: 16px;
                transition: background-color 0.3s ease, transform 0.3s ease;
            }}
            .button:hover {{
                background-color: #1B5E20;
                transform: translateY(-2px);
            }}
            .footer {{
                background-color: #f8f8f8;
                padding: 20px;
                text-align: center;
                font-size: 14px;
                color: #666666;
            }}
            .divider {{
                border-top: 1px solid #e0e0e0;
                margin: 30px 0;
            }}
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="cid:logo" alt="Immo Yes Logo" class="logo">
                <h1>Ihr Auftrag ist fertig!</h1>
            </div>
            <div class="content">
                <p>Sehr geehrter Kunde,</p>
                <p>Wir freuen uns, Ihnen mitteilen zu können, dass Ihr Auftrag abgeschlossen ist.</p>
                <p>Sie können den fertigen Auftrag hier ansehen:</p>
                <a href="http://auftrag.immoyes.com/project_details.php?id={project.id}" class="button">Auftrag ansehen</a>
                <div class="divider"></div>
                <p>Vielen Dank, dass Sie unseren Service genutzt haben.</p>
            </div>
            <div class="footer">
                <p>Dies ist eine automatische Nachricht. Bitte antworten Sie nicht direkt auf diese E-Mail.</p>
                <p>&copy; 2024 Immo Yes. Alle Rechte vorbehalten.</p>
            </div>
        </div>
    </body>
    </html>
    """
    
    msg = Message(subject,
                  sender='ImmoYes<portal@immoyes.com>',
                  recipients=[recipient],
                  bcc=["jansen.tobias@gmail.com"])
    msg.html = body

    # Attach the logo
    with app.open_resource("static/logo.png", "rb") as logo:
        msg.attach("logo.png", "image/png", logo.read(), "inline", headers={'Content-ID': '<logo>'})

    mail.send(msg)

@app.route('/admin/project/<int:project_id>/status', methods=['POST'])
@token_required
def update_project_status(current_user, project_id):
    if not current_user.is_admin:
        return jsonify({'message': 'Admin access required'}), 403

    project = Project.query.get(project_id)
    if not project:
        return jsonify({'message': 'Project not found'}), 404

    data = request.get_json()
    new_status = data.get('status')
    
    if not new_status:
        return jsonify({'message': 'No status provided'}), 400

    project.status = new_status
    db.session.commit()

    if new_status.lower() == 'abgeschlossen':
        try:
            project_owner = User.query.get(project.user_id)
            if project_owner and project_owner.email:
                send_styled_email(project, project_owner.email)
                current_app.logger.info(f"Status update email sent for Project {project.id} to {project_owner.email}")
            else:
                current_app.logger.warning(f"Could not send email for Project {project.id}: Owner not found or no email address")
        except Exception as e:
            current_app.logger.error(f"Error sending status update email for Project {project.id}: {str(e)}")
    
    return jsonify({'message': 'Project status updated successfully'}), 200


@app.route('/admin/project/<int:project_id>/upload-final', methods=['POST'])
@token_required
def upload_final_images(current_user, project_id):
    if not current_user.is_admin:
        return jsonify({'message': 'Admin access required'}), 403

    project = Project.query.get(project_id)
    if not project:
        return jsonify({'message': 'Project not found'}), 404

    if 'files' not in request.files:
        return jsonify({'message': 'No file part'}), 400

    files = request.files.getlist('files')
    final_image_links = []

    for file in files:
        if file and allowed_file(file.filename):
            filename = secure_filename(file.filename)
            file_path = os.path.join(app.config['FINALIZED_UPLOAD_FOLDER'], filename)
            file.save(file_path)
            final_image_links.append(file_path)

    project.final_image_links = json.dumps(final_image_links)
    project.status = 'abgeschlossen'
    db.session.commit()

    return jsonify({'message': 'Final images uploaded and project marked as completed'}), 200


@app.route('/admin/project/<int:project_id>/upload-final/<int:image_index>', methods=['POST'])
@token_required
def upload_single_final_image(current_user, project_id, image_index):
    if not current_user.is_admin:
        return jsonify({'message': 'Admin access required'}), 403

    project = Project.query.get(project_id)
    if not project:
        return jsonify({'message': 'Project not found'}), 404

    if 'file' not in request.files:
        return jsonify({'message': 'No file part'}), 400

    file = request.files['file']
    if file and allowed_file(file.filename):
        filename = secure_filename(f"final_{project_id}_{image_index}_{file.filename}")
        file_path = os.path.join(app.config['FINALIZED_UPLOAD_FOLDER'], filename)
        file.save(file_path)

        final_image_links = json.loads(project.final_image_links) if project.final_image_links else []
        
        # Update or add just the filename at the correct index
        if image_index < len(final_image_links):
            final_image_links[image_index] = filename
        else:
            # Pad the list with None values if necessary
            final_image_links.extend([None] * (image_index - len(final_image_links) + 1))
            final_image_links[image_index] = filename

        project.final_image_links = json.dumps(final_image_links)
        if all(final_image_links) and len(final_image_links) == len(json.loads(project.image_links)):
            project.status = 'abgeschlossen'
        db.session.commit()

        return jsonify({'message': 'Final image uploaded successfully'}), 200
    else:
        return jsonify({'message': 'Invalid file'}), 400

@app.route('/project/<int:project_id>/final-images', methods=['GET'])
@token_required
def get_final_images(current_user, project_id):
    project = Project.query.filter_by(id=project_id, user_id=current_user.id).first()
    if not project:
        return jsonify({'message': 'Project not found'}), 404

    final_image_links = json.loads(project.final_image_links) if project.final_image_links else []
    return jsonify({'final_images': final_image_links})

@app.route('/project/<int:project_id>/download-final-images', methods=['GET'])
@token_required
def download_final_images(current_user, project_id):
    try:
        project = Project.query.get(project_id)
        if not project or project.user_id != current_user.id:
            return jsonify({'message': 'Project not found or unauthorized'}), 404

        final_images = json.loads(project.final_image_links) if project.final_image_links else []
        
        if not final_images:
            return jsonify({'message': 'No final images available for this project'}), 404

        memory_file = io.BytesIO()
        with zipfile.ZipFile(memory_file, 'w') as zf:
            for i, image_filename in enumerate(final_images):
                # Construct the full path using the new format
                full_path = f'/var/www/auftrag.immoyes.com/finalized_uploads/{image_filename}'
                if os.path.exists(full_path):
                    # Use just the filename for the archive
                    archive_name = f'final_image_{i+1}{os.path.splitext(image_filename)[1]}'
                    zf.write(full_path, archive_name)
                else:
                    app.logger.warning(f"File not found: {full_path}")
        
        memory_file.seek(0)
        return send_file(
            memory_file,
            mimetype='application/zip',
            as_attachment=True,
            download_name=f'project_{project_id}_final_images.zip'
        )
    except Exception as e:
        app.logger.error(f"Error in download_final_images: {str(e)}")
        return jsonify({'message': 'An error occurred while processing your request'}), 500
    

@app.route('/admin/project/<int:project_id>/complete', methods=['POST'])
@token_required
def complete_project(current_user, project_id):
    if not current_user.is_admin:
        return jsonify({'message': 'Admin access required'}), 403

    project = Project.query.get(project_id)
    if not project:
        return jsonify({'message': 'Project not found'}), 404

    project.status = 'Abgeschlossen'
    db.session.commit()

    return jsonify({'message': 'Project marked as completed'}), 200

@app.route('/admin/projects', methods=['GET'])
@token_required
def get_all_projects(current_user):
    if not current_user.is_admin:
        return jsonify({'message': 'Admin access required'}), 403
    
    projects = Project.query.all()
    project_list = []
    for project in projects:
        user = User.query.get(project.user_id)
        images = Image.query.filter_by(project_id=project.id).all()
        image_list = [{'file_path': img.file_path, 'room_type': img.room_type, 'notes': img.notes} for img in images]
        
        project_data = {
            'id': project.id,
            'name': project.name,
            'description': project.description,
            'status': project.status,
            'cost': project.cost,
            'furniture_style': project.furniture_style,
            'created_at': project.created_at.isoformat() if project.created_at else None,
            'username': user.username if user else 'Unknown',
            'images': image_list,
            'final_image_links': project.final_image_links
        }
        print(f"Project {project.id} final_image_links: {project.final_image_links}")  # Add this line
        project_list.append(project_data)
    
    return jsonify(project_list)
    




# OpenAI API configuration
OPENAI_API_KEY = "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"  # Hardcoded API key
OPENAI_API_URL = "https://api.openai.com/v1/chat/completions"

@app.route('/generate-briefing/<int:project_id>', methods=['POST'])
@token_required
def generate_briefing(current_user, project_id):
    logging.info(f"Received request to generate briefing for project ID: {project_id}")

    # Lookup project in database
    project = Project.query.filter_by(id=project_id, user_id=current_user.id).first()
    if not project:
        logging.error(f"Project with ID {project_id} not found")
        return jsonify({"error": "Project not found"}), 404

    # Fetch associated images and room types
    images = Image.query.filter_by(project_id=project.id).all()
    room_details = [f"Room: {image.room_type}\nNotes: {image.notes}" for image in images if image.room_type]

    # Determine the project type and set appropriate instructions
    project_type = project.project_type.lower() if project.project_type else "unknown"
    
    if project_type == 'virtual homestaging':
        project_instructions = """
Important: This is a Virtual Homestaging project. Follow these strict guidelines:
- Do NOT remove any existing furniture or items in the room.
- Do NOT change wall colors, flooring, or any permanent fixtures.
- ONLY add new furniture and decor items to enhance the space.
- Work around existing elements to create an appealing staged look.
"""
    elif project_type == 'virtual renovation':
        project_instructions = """
This is a Virtual Renovation project. You have full creative freedom:
- You CAN change wall colors, flooring, and permanent fixtures.
- You CAN remove existing furniture and items.
- Suggest comprehensive changes to completely transform the space.
"""
    else:
        project_instructions = """
The project type is not specified. Please provide general recommendations for improving the space:
- Suggest furniture and decor additions that would enhance the room's appeal.
- Recommend color schemes that would work well with typical room layouts.
- Provide general styling tips to improve the overall look of the space.
"""

    # Prepare prompt using project details
    prompt = f"""As a professional interior design expert, create a detailed project brief for our agency team to execute the following project:

Project Overview:
- Project Name: {project.name}
- Project Type: {project_type}
- Overall Furniture Style: {project.furniture_style or "Not specified"}
- Total Budget: ${project.cost or "Not specified"}
- Current Status: {project.status or "Not specified"}
- Client Description: {project.description or "Not provided"}

{project_instructions}

Detailed Room Instructions:

{chr(10).join(room_details)}

For each room listed above, please provide:
1. Suggested furniture pieces and decor items that match the overall style.
2. Color scheme recommendations that complement the furniture style and existing elements.
3. Styling elements or accessories to enhance the room's appeal.
4. Lighting suggestions to create the right ambiance.
5. Any particular challenges or focal points to address based on the client's notes.

General Guidelines:
- Ensure consistency with the overall style throughout the project.
- Consider the project type and its specific restrictions or freedoms when making recommendations.
- Aim for a balance between aesthetic appeal and practical functionality.
- Keep the total budget in mind when suggesting furniture and decor.

Additional Notes:
- Pay special attention to any specific requests or concerns mentioned in the room notes.
- If any room lacks specific notes, use your expertise to suggest improvements that align with the overall project style and type.

Please provide a comprehensive briefing that our agency can use to execute this project effectively, ensuring client satisfaction and a cohesive final result."""

    logging.info(f"Generated prompt for project {project_id}")

    try:
        # Prepare the request to OpenAI API
        headers = {
            "Authorization": f"Bearer {OPENAI_API_KEY}",
            "Content-Type": "application/json"
        }
        payload = {
            "model": "gpt-3.5-turbo",
            "messages": [
                {"role": "system", "content": "You are a professional interior design expert. Your task is to create detailed project briefs for agency teams to execute interior design projects, adhering to the specific requirements of each project type."},
                {"role": "user", "content": prompt}
            ],
            "max_tokens": 1000,  # Increased token limit for more detailed response
            "n": 1,
            "temperature": 0.7,
        }

        logging.info("Sending request to OpenAI API")
        # Make the request to OpenAI API
        response = requests.post(OPENAI_API_URL, json=payload, headers=headers)
        
        # Log the raw response
        logging.debug(f"Raw API response: {response.text}")
        
        response.raise_for_status()

        # Extract the generated briefing from the API response
        response_data = response.json()
        briefing = response_data['choices'][0]['message']['content'].strip()

        logging.info("Successfully generated briefing")
        return jsonify({"briefing": briefing}), 200

    except requests.exceptions.RequestException as e:
        logging.error(f"Error making request to OpenAI API: {str(e)}")
        logging.debug(f"Response content: {e.response.text if e.response else 'No response content'}")
        return jsonify({"error": "Failed to communicate with OpenAI API", "details": str(e)}), 500
    except KeyError as e:
        logging.error(f"Unexpected response format from OpenAI API: {str(e)}")
        return jsonify({"error": "Received unexpected response format from OpenAI API"}), 500
    except Exception as e:
        logging.exception(f"Unexpected error: {str(e)}")
        return jsonify({"error": "An unexpected error occurred"}), 500



analytics = Blueprint('analytics', __name__)

@analytics.route('/api/analytics/dashboard', methods=['GET'])
def get_dashboard_data():
    # User Statistics
    total_users = User.query.count()
    admin_users = User.query.filter_by(is_admin=True).count()
    operator_users = User.query.filter_by(is_operator=True).count()

    # Project Statistics
    total_projects = Project.query.count()
    projects_by_status = db.session.query(Project.status, func.count(Project.id)).group_by(Project.status).all()
    projects_by_type = db.session.query(Project.project_type, func.count(Project.id)).group_by(Project.project_type).all()

    # Financial Statistics
    total_revenue = db.session.query(func.sum(Transaction.amount)).scalar() or 0
    avg_project_cost = db.session.query(func.avg(Project.cost)).scalar() or 0
    
    # Monthly Revenue (last 6 months)
    six_months_ago = datetime.utcnow() - timedelta(days=180)
    monthly_revenue = db.session.query(
        func.strftime('%Y-%m', Transaction.timestamp).label('month'),
        func.sum(Transaction.amount).label('revenue')
    ).filter(Transaction.timestamp >= six_months_ago).group_by('month').order_by('month').all()

    # Engagement Statistics
    total_messages = ChatMessage.query.count()
    avg_messages_per_project = db.session.query(func.avg(
        db.session.query(func.count(ChatMessage.id)).filter(ChatMessage.user_id == Project.user_id).correlate(Project).as_scalar()
    )).scalar() or 0

    # Messages Trend (last 7 days)
    seven_days_ago = datetime.utcnow() - timedelta(days=7)
    messages_trend = db.session.query(
        func.strftime('%w', ChatMessage.timestamp).label('day'),
        func.count(ChatMessage.id).label('messages')
    ).filter(ChatMessage.timestamp >= seven_days_ago).group_by('day').order_by('day').all()

    return jsonify({
        'userStats': {
            'totalUsers': total_users,
            'adminUsers': admin_users,
            'operatorUsers': operator_users
        },
        'projectStats': {
            'totalProjects': total_projects,
            'projectsByStatus': [{'name': status, 'value': count} for status, count in projects_by_status],
            'projectsByType': [{'name': p_type, 'value': count} for p_type, count in projects_by_type]
        },
        'financialStats': {
            'totalRevenue': float(total_revenue),
            'averageProjectCost': float(avg_project_cost),
            'monthlyRevenue': [{'month': month, 'revenue': float(revenue)} for month, revenue in monthly_revenue]
        },
        'engagementStats': {
            'totalMessages': total_messages,
            'averageMessagesPerProject': float(avg_messages_per_project),
            'messagesTrend': [{'day': day, 'messages': messages} for day, messages in messages_trend]
        }
    })

@analytics.route('/api/analytics/users', methods=['GET'])
def get_user_analytics():
    total_users = User.query.count()
    new_users_last_30_days = User.query.filter(User.created_at >= (datetime.utcnow() - timedelta(days=30))).count()
    users_with_projects = db.session.query(func.count(func.distinct(Project.user_id))).scalar()

    return jsonify({
        'totalUsers': total_users,
        'newUsersLast30Days': new_users_last_30_days,
        'usersWithProjects': users_with_projects
    })

@analytics.route('/api/analytics/projects', methods=['GET'])
def get_project_analytics():
    total_projects = Project.query.count()
    active_projects = Project.query.filter(Project.status != 'Abgeschlossen').count()
    avg_project_duration = db.session.query(func.avg(
        func.julianday(func.coalesce(Project.completion_date, func.current_timestamp())) - func.julianday(Project.created_at)
    )).scalar()

    return jsonify({
        'totalProjects': total_projects,
        'activeProjects': active_projects,
        'averageProjectDuration': float(avg_project_duration) if avg_project_duration else None
    })

@analytics.route('/api/analytics/financials', methods=['GET'])
def get_financial_analytics():
    total_revenue = db.session.query(func.sum(Transaction.amount)).scalar() or 0
    avg_transaction_value = db.session.query(func.avg(Transaction.amount)).scalar() or 0
    
    return jsonify({
        'totalRevenue': float(total_revenue),
        'averageTransactionValue': float(avg_transaction_value)
    })


@app.route('/api/analytics/dashboard', methods=['GET'])
def get_dashboard_data():
    date_range = request.args.get('dateRange', 'all')
    
    if date_range == 'last7days':
        start_date = datetime.utcnow() - timedelta(days=7)
    else:
        start_date = datetime.min

    # User Statistics
    total_users = User.query.count()
    admin_users = User.query.filter_by(is_admin=True).count()
    operator_users = User.query.filter_by(is_operator=True).count()

    # Project Statistics
    project_query = Project.query.filter(Project.created_at >= start_date)
    total_projects = project_query.count()
    projects_by_status = db.session.query(Project.status, func.count(Project.id)).filter(Project.created_at >= start_date).group_by(Project.status).all()
    projects_by_type = db.session.query(Project.project_type, func.count(Project.id)).filter(Project.created_at >= start_date).group_by(Project.project_type).all()

    # Financial Statistics
    total_revenue = db.session.query(func.sum(Transaction.amount)).filter(Transaction.timestamp >= start_date).scalar() or 0
    avg_project_cost = db.session.query(func.avg(Project.cost)).filter(Project.created_at >= start_date).scalar() or 0
    
    # Monthly Revenue
    if date_range == 'last7days':
        revenue_data = db.session.query(
            func.date(Transaction.timestamp).label('date'),
            func.sum(Transaction.amount).label('revenue')
        ).filter(Transaction.timestamp >= start_date).group_by('date').order_by('date').all()
        monthly_revenue = [{'month': date.strftime('%Y-%m-%d'), 'revenue': float(revenue)} for date, revenue in revenue_data]
    else:
        six_months_ago = datetime.utcnow() - timedelta(days=180)
        monthly_revenue = db.session.query(
            func.concat(extract('year', Transaction.timestamp), '-', func.lpad(extract('month', Transaction.timestamp), 2, '0')).label('month'),
            func.sum(Transaction.amount).label('revenue')
        ).filter(Transaction.timestamp >= six_months_ago).group_by('month').order_by('month').all()
        monthly_revenue = [{'month': month, 'revenue': float(revenue)} for month, revenue in monthly_revenue]

    # Engagement Statistics
    total_messages = ChatMessage.query.filter(ChatMessage.timestamp >= start_date).count()
    avg_messages_per_project = db.session.query(func.avg(
        db.session.query(func.count(ChatMessage.id))
        .filter(ChatMessage.user_id == Project.user_id, ChatMessage.timestamp >= start_date)
        .correlate(Project).as_scalar()
    )).scalar() or 0

    # Messages Trend
    if date_range == 'last7days':
        messages_trend = db.session.query(
            func.date(ChatMessage.timestamp).label('day'),
            func.count(ChatMessage.id).label('messages')
        ).filter(ChatMessage.timestamp >= start_date).group_by('day').order_by('day').all()
        messages_trend = [{'day': day.strftime('%a'), 'messages': messages} for day, messages in messages_trend]
    else:
        # Last 30 days trend for 'all' time range
        thirty_days_ago = datetime.utcnow() - timedelta(days=30)
        messages_trend = db.session.query(
            func.date(ChatMessage.timestamp).label('day'),
            func.count(ChatMessage.id).label('messages')
        ).filter(ChatMessage.timestamp >= thirty_days_ago).group_by('day').order_by('day').all()
        messages_trend = [{'day': day.strftime('%Y-%m-%d'), 'messages': messages} for day, messages in messages_trend]

    return jsonify({
        'userStats': {
            'totalUsers': total_users,
            'adminUsers': admin_users,
            'operatorUsers': operator_users
        },
        'projectStats': {
            'totalProjects': total_projects,
            'projectsByStatus': [{'name': status, 'value': count} for status, count in projects_by_status],
            'projectsByType': [{'name': p_type, 'value': count} for p_type, count in projects_by_type]
        },
        'financialStats': {
            'totalRevenue': float(total_revenue),
            'averageProjectCost': float(avg_project_cost),
            'monthlyRevenue': monthly_revenue
        },
        'engagementStats': {
            'totalMessages': total_messages,
            'averageMessagesPerProject': float(avg_messages_per_project),
            'messagesTrend': messages_trend
        }
    })
# In your main block
if __name__ == '__main__':
    logger.info("Starting the application")
    if not os.path.exists(UPLOAD_FOLDER):
        os.makedirs(UPLOAD_FOLDER)
        logger.info(f"Created UPLOAD_FOLDER: {UPLOAD_FOLDER}")
    if not os.path.exists(FINALIZED_UPLOAD_FOLDER):
        os.makedirs(FINALIZED_UPLOAD_FOLDER)
        logger.info(f"Created FINALIZED_UPLOAD_FOLDER: {FINALIZED_UPLOAD_FOLDER}")
    
    with app.app_context():
        db.create_all()
        logger.info("Database tables created")
    
    init_scheduler()  # Initialize and start the scheduler
    
    logger.info("Starting Flask server on port 1000")
    app.run(host='0.0.0.0', port=5000, debug=False)
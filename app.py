import os
import json
import uuid
from flask import Flask, render_template, request, redirect, url_for, flash, send_from_directory

app = Flask(__name__)
app.secret_key = os.environ.get('FLASK_SECRET_KEY', 'your-secret-key-here')

# Create necessary directories if they don't exist
os.makedirs('books', exist_ok=True)
os.makedirs('metadata', exist_ok=True)

@app.route('/')
def library():
    books = []
    for filename in os.listdir('metadata'):
        if filename.endswith('.json'):
            with open(os.path.join('metadata', filename)) as f:
                metadata = json.load(f)
                metadata['id'] = filename[:-5]  # Remove .json extension
                books.append(metadata)
    return render_template('library.html', books=books)

@app.route('/upload', methods=['POST'])
def upload():
    if 'book' not in request.files:
        flash('No file selected')
        return redirect(url_for('library'))
    
    book = request.files['book']
    if book.filename == '':
        flash('No file selected')
        return redirect(url_for('library'))
    
    if not book.filename.endswith('.mobi'):
        flash('Only .mobi files are allowed')
        return redirect(url_for('library'))
    
    book_id = str(uuid.uuid4())
    book.save(os.path.join('books', f'{book_id}.mobi'))
    
    metadata = {
        'title': request.form.get('title', 'Untitled'),
        'author_first': request.form.get('author_first', ''),
        'author_last': request.form.get('author_last', ''),
        'series': request.form.get('series', ''),
        'series_position': request.form.get('series_position', ''),
        'genre': request.form.get('genre', ''),
        'filename': f'{book_id}.mobi'
    }
    
    with open(os.path.join('metadata', f'{book_id}.json'), 'w') as f:
        json.dump(metadata, f, indent=4)
    
    flash('Book uploaded successfully')
    return redirect(url_for('library'))

@app.route('/download/<book_id>')
def download(book_id):
    try:
        with open(os.path.join('metadata', f'{book_id}.json')) as f:
            metadata = json.load(f)
        return send_from_directory('books', metadata['filename'])
    except:
        flash('Book not found')
        return redirect(url_for('library'))

@app.route('/edit/<book_id>', methods=['GET', 'POST'])
def edit_metadata(book_id):
    try:
        with open(os.path.join('metadata', f'{book_id}.json')) as f:
            metadata = json.load(f)
        
        if request.method == 'POST':
            metadata.update({
                'title': request.form.get('title'),
                'author_first': request.form.get('author_first'),
                'author_last': request.form.get('author_last'),
                'series': request.form.get('series'),
                'series_position': request.form.get('series_position'),
                'genre': request.form.get('genre')
            })
            
            with open(os.path.join('metadata', f'{book_id}.json'), 'w') as f:
                json.dump(metadata, f, indent=4)
            
            flash('Metadata updated successfully')
            return redirect(url_for('library'))
        
        return render_template('edit_metadata.html', book=metadata, book_id=book_id)
    except:
        flash('Book not found')
        return redirect(url_for('library'))

@app.route('/delete/<book_id>')
def delete_book(book_id):
    try:
        with open(os.path.join('metadata', f'{book_id}.json')) as f:
            metadata = json.load(f)
        
        os.remove(os.path.join('books', metadata['filename']))
        os.remove(os.path.join('metadata', f'{book_id}.json'))
        flash('Book deleted successfully')
    except:
        flash('Error deleting book')
    return redirect(url_for('library'))
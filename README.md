# Chat Application

This is a PHP-based chat application that allows users to register, log in, and send various types of messages (text, images, videos, PDF, DOCX) to other users. Users can also edit their profile and change their password.

## Features

- User Registration
- User Login
- Send and Receive Text Messages
- Send and Receive Images, Videos, PDF, DOCX files
- Edit Profile
- Change Password

## Requirements

- PHP 7.4 or higher
- MySQL 5.6 or higher
- Apache/Nginx Server

## Installation

1. **Clone the Repository**

    ```sh
    git clone https://github.com/your-username/chat-application.git
    cd chat-application
    ```

2. **Configure the Database**

    Create a MySQL database and import the provided SQL file:

    ```sql
    CREATE DATABASE chat_app;
    USE chat_app;
    SOURCE path/to/database.sql;
    ```

3. **Update the Database Configuration**

    Update the `config.php` file with your database credentials:

    ```php
    <?php
    $servername = "localhost";
    $username = "your_db_username";
    $password = "your_db_password";
    $dbname = "chat_app";
    ?>
    ```

4. **Upload Files to Server**

    Upload the project files to your web server directory (e.g., `htdocs` for XAMPP, `www` for WAMP).

5. **Set Permissions**

    Ensure that the `uploads` directory is writable:

    ```sh
    chmod -R 755 uploads
    ```

## Usage

1. **Register**

    Open the application in your web browser and navigate to the registration page to create a new account.

2. **Login**

    Log in with your registered credentials.

3. **Chat**

    - To send a text message, type your message and click "Send".
    - To send an image, video, PDF, or DOCX file, use the file upload option.

4. **Profile Management**

    - Edit your profile information from the "Profile" section.
    - Change your password from the "Change Password" section.

## Folder Structure

- `config.php`: Database configuration file
- `register.php`: User registration script
- `login.php`: User login script
- `chat.php`: Main chat interface
- `profile.php`: Profile management interface
- `change_password.php`: Password change s

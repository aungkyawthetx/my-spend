## Budget Board
A simple and efficient web-based expense tracking application built with PHP to help you manage your personal finances.

## Features
Add Expenses - Record your daily expenses with categories

Expense Categories - Organize expenses by custom categories (Food, Transport, Entertainment, etc.)

View History - Browse through your expense history with filtering options

Monthly Reports - Visualize your spending patterns with monthly summaries

Budget Tracking - Set and monitor monthly budgets

Data Export - Export your expense data to CSV format

## Tech Stack
Backend: PHP (Native)

Frontend: HTML, Tailwind CSS, JavaScript

Database: MySQL

Server: Apache/XAMPP

## Prerequisites
Before running this project, make sure you have:

PHP 7.4 or higher

MySQL 5.7 or higher

Web server (Apache/Nginx) or XAMPP/WAMP

Composer (for dependency management)

## Installation
Clone the repository

git clone https://github.com/aungkyawthetx/budget-board.git 
cd budget-board
Set up the database

## Update database credentials:

DB_HOST=localhost
DB_NAME=expense_tracker
DB_USER="root"
DB_PASS="your_password"

## Run database migrations

php migrations/setup.php
## Start your local server
php -S localhost:8000
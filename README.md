# EsasyExam

EsasyExam is a web-based examination and learning management platform designed to support digital assessment, question bank management, and AI-assisted content generation in educational environments.

The system is built to simplify exam creation, improve question organization, and provide scalable learning workflows for administrators, teachers, and students.

---

## Overview

EsasyExam provides an integrated environment where educational institutions can manage subjects, users, examinations, and learning activities through a modern and responsive interface.

The platform focuses on:

* Centralized question bank management
* Examination creation and distribution
* AI-assisted question generation
* Student learning support
* Administrative academic management

---

## Core Features

### Authentication & Role Management

Supports multiple user roles:

* Administrator
* Teacher
* Student

Each role has dedicated access permissions and dashboards.

---

### Dashboard System

#### Administrator Dashboard

* Manage classes
* Manage teachers
* Manage students
* Manage subjects
* Monitor examinations

#### Teacher Dashboard

* Create and manage question banks
* Import questions via Excel
* Generate questions using AI
* Build examinations
* Publish and maintain assessments

#### Student Dashboard

* Access learning materials
* Participate in examinations
* Track learning progress

---

### Question Bank

Question management includes:

* Multiple choice questions
* Essay questions
* Question categorization by subject
* Difficulty levels
* Draft and publish workflow
* Image support
* Search and filtering
* Excel import/export

---

### Examination Module

Capabilities include:

* Exam creation
* Subject-based question selection
* Automatic distribution
* Answer submission
* Result tracking

---

### AI Question Generator

Integrated AI functionality enables:

* Automatic question generation
* Subject-based content generation
* Editable generated output
* Save generated questions directly into the question bank

---

## Technology Stack

### Backend

* Laravel 13
* PHP 8.3

### Frontend

* Blade
* Tailwind CSS
* Alpine.js
* Font Awesome

### Database

* MySQL

### Additional Tools

* Laravel Vite
* PhpSpreadsheet
* Laravel Excel

---

## Project Structure

```plaintext
app/
resources/
routes/
public/
database/
storage/
```

---

## Installation

Clone repository:

```bash
git clone https://github.com/chiperfox4005/EsasyExam.git
```

Enter project directory:

```bash
cd EsasyExam
```

Install dependencies:

```bash
composer install
npm install
```

Create environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Configure database inside `.env`.

Run migration:

```bash
php artisan migrate
```

Build frontend:

```bash
npm run build
```

Start development server:

```bash
php artisan serve
```

---

## Development Commands

Run development environment:

```bash
npm run dev
```

Clear cache:

```bash
php artisan optimize:clear
```

Run migrations:

```bash
php artisan migrate
```

---

## Repository

GitHub Repository:

https://github.com/chiperfox4005/EsasyExam

---

## Roadmap

Planned improvements:

* Learning analytics
* AI performance recommendations
* Gamification system
* Student leaderboard
* Badge system
* Real-time monitoring
* Mobile optimization

---

## License

This project is developed for educational and research purposes.

All rights reserved.

# EsasyExam

EsasyExam is a modern web-based examination and learning platform designed to streamline academic assessment, question management, and digital learning experiences.

Built using Laravel and modern web technologies, EsasyExam provides an integrated environment for administrators, teachers, and students to manage educational activities efficiently through a centralized system.

---

## Introduction

Educational institutions increasingly require flexible and scalable systems to support examinations, learning processes, and content management.

EsasyExam addresses these needs by combining:

* Examination management
* Centralized question banks
* AI-assisted question generation
* Learning support tools
* User role separation
* Responsive and modern user interfaces

The platform is designed with extensibility in mind to support future educational innovations.

---

## Key Capabilities

### User & Access Management

Role-based architecture ensures controlled access across the system.

Supported roles:

* Administrator
* Teacher
* Student

Each role receives dedicated dashboards and feature access.

---

## Administration Module

Administrator capabilities include:

* Manage academic classes
* Manage teacher accounts
* Manage student accounts
* Organize subjects
* Supervise examinations
* Monitor platform usage

---

## Teacher Workspace

Teachers can:

* Build and manage question banks
* Create multiple-choice and essay questions
* Upload question images
* Categorize questions by subject and difficulty
* Import questions from Excel
* Generate questions using AI
* Create and publish examinations

---

## Student Experience

Students can:

* Access learning modules
* Join examinations
* Submit answers digitally
* Review examination results
* Track learning progress

---

## Question Bank System

Question management supports:

* Multiple Choice Questions
* Essay Questions
* Question difficulty classification
* Draft and publish workflow
* Search and filtering
* Image attachments
* Excel import support
* AI-generated content

---

## Examination System

Core examination features:

* Exam scheduling
* Subject-based question selection
* Automatic question loading
* Student answer collection
* Result processing

---

## AI Question Generation

EsasyExam integrates AI-assisted workflows that enable:

* Automatic question creation
* Subject-aware generation
* Editable generated outputs
* Direct saving into question banks

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

### Additional Packages

* Laravel Vite
* Laravel Excel
* PhpSpreadsheet

---

## Project Structure

```plaintext
app/
bootstrap/
config/
database/
public/
resources/
routes/
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

Install backend dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

Create environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Configure database credentials inside `.env`.

Run database migration:

```bash
php artisan migrate
```

Build frontend assets:

```bash
npm run build
```

Start development server:

```bash
php artisan serve
```

---

## Development Commands

Development server:

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

Repository URL:

https://github.com/chiperfox4005/EsasyExam

---

## Future Development

Planned enhancements:

* Learning analytics
* AI-based student recommendations
* Gamification features
* Badge system
* Leaderboards
* Real-time monitoring
* Mobile responsiveness improvements

---

## License

This project is developed for educational and research purposes.

© FikryTech — All Rights Reserved.

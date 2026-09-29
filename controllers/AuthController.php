<?php
declare(strict_types=1);

final class AuthController
{
    public function __construct(private User $users) {}

    public function register(): void
    {
        require_guest();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf($_POST['csrf_token'] ?? null)) {
                flash('error', 'Your form expired. Please try again.');
                redirect('/register');
            }

            $errors = validate_registration($_POST);
            $email = trim((string)($_POST['email'] ?? ''));

            if (!$errors && $this->users->findByEmail($email)) {
                $errors['email'] = 'An account with this email already exists.';
            }

            if ($errors) {
                $_SESSION['form_errors'] = $errors;
                $_SESSION['old'] = $_POST;
                redirect('/register');
            }

            $id = $this->users->create(
                trim((string)$_POST['name']),
                $email,
                (string)$_POST['password']
            );
            login_user($id);
            flash('success', 'Welcome to JobTrack!');
            redirect('/dashboard');
        }

        render('auth/register', [
            'title' => 'Create account',
            'errors' => $_SESSION['form_errors'] ?? [],
        ]);
        unset($_SESSION['form_errors']);
    }

    public function login(): void
    {
        require_guest();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf($_POST['csrf_token'] ?? null)) {
                flash('error', 'Your form expired. Please try again.');
                redirect('/login');
            }

            $email = trim((string)($_POST['email'] ?? ''));
            $password = (string)($_POST['password'] ?? '');
            $user = $this->users->findByEmail($email);

            if (!$user || !password_verify($password, $user['password_hash'])) {
                flash('error', 'Invalid email or password.');
                redirect('/login');
            }

            login_user((int)$user['id']);
            flash('success', 'Welcome back, ' . $user['name'] . '!');
            redirect('/dashboard');
        }

        render('auth/login', ['title' => 'Login']);
    }

    public function logout(): void
    {
        logout_user();
        redirect('/login');
    }
}

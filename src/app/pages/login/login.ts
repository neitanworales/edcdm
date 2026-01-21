import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { ReactiveFormsModule, FormGroup, FormControl, Validators } from '@angular/forms';
import { AuthService } from '../../core/services/auth.service';
import { LogInDao } from '../../core/dao/LogInDao';

@Component({
  selector: 'app-login',
  imports: [ReactiveFormsModule],
  templateUrl: './login.html',
  styleUrl: './login.css',
})
export class Login {

  loginForm = new FormGroup({
    email: new FormControl('', [Validators.required, Validators.email]),
    password: new FormControl('', [Validators.required]),
    rememberMe: new FormControl(false)
  });
  loginError: string | null = null;

  constructor(
    private logInDao: LogInDao,
    private authService: AuthService, 
    private router: Router
  ) {}

  login(){
    if (this.loginForm.valid) {
      const email = this.loginForm.value.email!;
      const password = this.loginForm.value.password!;
      
      
      this.logInDao.login(email, password).subscribe({
        next: (response) => {
          if (response.session?.token) {
            this.onLogin(response.session.token, response.session.user);
          }
        },
        error: (error) => {
          this.loginError = 'Login failed. Please check your credentials.';
          console.error('Login failed', error);
        }
      });
    }
  }

  onLogin(token: string, userData: any) {
    this.authService.login(token, userData);
    this.router.navigate(['/dashboard']);
  }

}

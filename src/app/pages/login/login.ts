import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../../core/services/auth.service';

@Component({
  selector: 'app-login',
  imports: [],
  templateUrl: './login.html',
  styleUrl: './login.css',
})
export class Login {

  constructor(
    private authService: AuthService, 
    private router: Router
  ) {}

  onLogin(token: string, userData: any) {
    this.authService.login(token, userData);
    this.router.navigate(['/dashboard']);
  }

}

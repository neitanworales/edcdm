import { Component } from '@angular/core';
import { ReactiveFormsModule, FormGroup, FormBuilder, Validators, AbstractControl, ValidationErrors } from '@angular/forms';
import { AppUser } from '../../core/models/AppUser';
import { LogInDao } from '../../core/api/LogInDao';
import { Observable, of, map, delay } from 'rxjs';

@Component({
  selector: 'app-user-register',
  imports: [ReactiveFormsModule],
  templateUrl: './user-register.html',
  styleUrl: './user-register.css',
})
export class UserRegister {
  form: FormGroup;
  user: AppUser | null = null;

  constructor(
    private fb: FormBuilder,
    private logInDao: LogInDao
  ) {
    this.form = this.fb.group({
      full_name: [''],
      email: ['', 
        [Validators.required, Validators.email],
        [this.emailExistsValidator.bind(this)] // validador asincrónico
      ],
      password: ['', Validators.required],
      confirmPassword: ['', Validators.required]
    }, { validators: this.passwordMatchValidator });
  }

  emailExistsValidator(control: AbstractControl): Observable<ValidationErrors | null> {
    if (!control.value) {
      return of(null);
    }
    
    return this.logInDao.checkEmailExists(control.value).pipe(
      map(response => response.exists ? { emailExists: true } : null),
      delay(500) 
    );
  }

  passwordMatchValidator(group: FormGroup): {[key: string]: boolean} | null {
    const password = group.get('password');
    const confirmPassword = group.get('confirmPassword');
    
    if (password && confirmPassword && password.value !== confirmPassword.value) {
      return { passwordMismatch: true };
    }
    return null;
  }

  save(): void {
    if (this.form.invalid) return;
    this.user = this.form.value as AppUser;
    this.user.role = 'user';
    this.logInDao.register(this.user).subscribe({
      next: (response) => {
        console.log('User registered successfully', response);
      },
      error: (error) => {
        console.error('Error registering user', error);
      }
    });
  }
}

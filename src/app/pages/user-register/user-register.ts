import { ChangeDetectorRef, Component } from '@angular/core';
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
  success: boolean = false;
  error: boolean = false;
  messageSuccess: string = 'Usuario registrado exitosamente';
  messageError: string = 'Error al registrar el usuario';

  constructor(
    private fb: FormBuilder,
    private logInDao: LogInDao,
    private changeDetector: ChangeDetectorRef
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
      delay(200) 
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
        this.success = true;
        this.error = false;
        this.form.reset();
        this.changeDetector.detectChanges();
      },
      error: (error) => {
        console.error('Error registering user', error);
        this.success = false;
        this.error = true;
        this.messageError = error.error?.message || 'Error al registrar el usuario';
        this.changeDetector.detectChanges();
      }
    });
  }
}

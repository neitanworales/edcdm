import { Component } from '@angular/core';
import { FormGroup, FormControl, Validators, ReactiveFormsModule } from '@angular/forms';
import { ChurchesDao } from '../../core/api/ChurchesDao';
import { Church } from '../../core/models/Church';

@Component({
  selector: 'app-register',
  imports: [ReactiveFormsModule],
  templateUrl: './register.html',
  styleUrl: './register.css',
  standalone: true,
})
export class Register {
  constructor(private churchesDao: ChurchesDao) {}

  churches: Church[] = [];
  registerForm = new FormGroup({
    first_name: new FormControl<string>('', { nonNullable: true, validators: [Validators.required, Validators.maxLength(100)] }),
    last_name: new FormControl<string>('', { nonNullable: true, validators: [Validators.required, Validators.maxLength(150)] }),
    email: new FormControl<string>('', { validators: [Validators.email, Validators.maxLength(150)] }),
    phone: new FormControl<string>('', { validators: [Validators.pattern(/^[0-9+()\s-]{7,}$/), Validators.maxLength(50)] }),
    church_id: new FormControl<number | null>(null),
    date_of_birth: new FormControl<string>(''),
    notes: new FormControl<string>(''),
  });

  submit() {
    if (this.registerForm.valid) {
      const payload = this.registerForm.value;
      console.log('Register student payload', payload);
      // TODO: Integrate with API (StudentController) via a Students service/DAO
    } else {
      this.registerForm.markAllAsTouched();
    }
  }

  ngOnInit(): void {
    this.churchesDao.list().subscribe({
      next: (items) => this.churches = items,
      error: (err) => console.error('Load churches failed', err)
    });
  }
}
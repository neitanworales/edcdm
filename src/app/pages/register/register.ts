import { ChangeDetectorRef, Component, OnInit } from '@angular/core';
import { FormGroup, FormControl, Validators, ReactiveFormsModule } from '@angular/forms';
import { ChurchesDao } from '../../core/api/ChurchesDao';
import { Church } from '../../core/models/Church';
import { StudentDao } from '../../core/api/StudentDao';
import { Student } from '../../core/models/Student';
import { DatePipe } from '@angular/common';

@Component({
  selector: 'app-register',
  imports: [ReactiveFormsModule, DatePipe],
  templateUrl: './register.html',
  styleUrl: './register.css',
  standalone: true,
})
export class Register implements OnInit {

  displayRegistrados = 'block';
  displaRegisterForm = 'none';

  students: Student[] = [];

  constructor(
    private churchesDao: ChurchesDao,
    private studentDao: StudentDao,
    private changeDetector: ChangeDetectorRef
  ) { }

  churches: Church[] = [];
  registerForm = new FormGroup({
    first_name: new FormControl<string>('', { nonNullable: true, validators: [Validators.required, Validators.maxLength(100)] }),
    last_name: new FormControl<string>('', { nonNullable: true, validators: [Validators.required, Validators.maxLength(150)] }),
    email: new FormControl<string>('', { validators: [Validators.email, Validators.maxLength(150)] }),
    phone: new FormControl<string>('', { validators: [Validators.pattern(/^[0-9+()\s-]{7,}$/), Validators.maxLength(50)] }),
    church_id: new FormControl<number>(0, { nonNullable: true, validators: [Validators.required] }),
    date_of_birth: new FormControl<string | null>(null),
    notes: new FormControl<string>(''),
  });

  submit() {
    if (this.registerForm.valid) {
      const payload = this.registerForm.value as Student;
      console.log('Register student payload', payload);
      this.studentDao.create(payload).subscribe({
        next: (response) => {
          console.log('Student registered successfully', response);
          this.registerForm.reset();
          this.changeDetector.detectChanges();
        },
        error: (err) => {
          console.error('Failed to register student', err);
          this.changeDetector.detectChanges();
        }
      });
    } else {
      this.registerForm.markAllAsTouched();
    }
  }

  ngOnInit(): void {
    this.loadStudents();
    this.churchesDao.list().subscribe({
      next: (items) => {
        this.churches = items;
        this.changeDetector.detectChanges();
      },
      error: (err) => {
        console.error('Load churches failed', err);
        this.changeDetector.detectChanges();
      }
    });
  }

  cambiarOpcion(opcion: number) {
    if (opcion === 1) {
      this.displayRegistrados = 'block';
      this.displaRegisterForm = 'none';
      this.loadStudents();
    } else if (opcion === 2) {
      this.displayRegistrados = 'none';
      this.displaRegisterForm = 'block';
    }
  }

  loadStudents() {
    this.studentDao.list().subscribe({
      next: (items) => {
        this.students = items.students;
        this.changeDetector.detectChanges();
      },
      error: (err) => {
        console.error('Load students failed', err);
        this.changeDetector.detectChanges();
      }
    });
  }
}
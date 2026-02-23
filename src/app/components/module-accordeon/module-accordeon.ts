import { Component, EventEmitter, Input, OnInit, Output, SimpleChanges } from '@angular/core';
import { Module } from '../../core/models/Module';
import { Root, student } from '../../core/models/SessionLesson';
import { StudentDao } from '../../core/api/StudentDao';
import { Student } from '../../core/models/Student';
import { StatusAttendance } from "../status-attendance/status-attendance";
import { TableAttendances } from "../table-attendances/table-attendances";

@Component({
  selector: 'app-module-accordeon',
  imports: [StatusAttendance],
  templateUrl: './module-accordeon.html',
  styleUrl: './module-accordeon.css',
  standalone: true
})
export class ModuleAccordeon implements OnInit {

  @Input()
  modules?: Module[];

  @Input()
  showLessons: boolean = false;

  @Input()
  root?: Root;

  unassignedStudents?: Student[];
  cohortId?: number;

  private selectedStudentIds = new Set<number>();

  @Output() ejecutarUpdate = new EventEmitter<void>();

  constructor(
    private studentDao: StudentDao
  ) { }

  ngOnInit(): void {

  }

  getUnassignedStudents(church_id: number, cohort_id: number): void {
    this.cohortId = cohort_id;
    this.selectedStudentIds.clear();
    this.studentDao.getUnassigned(church_id).subscribe({
      next: (response) => {
        this.unassignedStudents = response.students;
        console.log("Unassigned students:", response.students);
      },
      error: (error) => {
        console.error("Error fetching unassigned students:", error);
      }
    });
  }

  isStudentSelected(studentId: number | undefined): boolean {
    if (typeof studentId !== 'number') return false;
    return this.selectedStudentIds.has(studentId);
  }

  onStudentToggle(studentId: number | undefined, checked: boolean): void {
    if (typeof studentId !== 'number') return;
    if (checked) {
      this.selectedStudentIds.add(studentId);
    } else {
      this.selectedStudentIds.delete(studentId);
    }
  }

  private getSelectedStudentIds(): number[] {
    return Array.from(this.selectedStudentIds);
  }

  agregar(): void {
    const selectedIds = this.getSelectedStudentIds();
    console.log("Agregar button clicked. Cohort ID:", this.cohortId, "Selected student IDs:", selectedIds);
    // Aquí puedes agregar la lógica para asignar los estudiantes seleccionados al módulo
    for (const studentId of selectedIds) {
      this.studentDao.addStudentsToCohort(studentId, this.cohortId!).subscribe({
        next: (response) => {
          console.log(`Student ${studentId} added to cohort ${this.cohortId}:`, response);
        },
        error: (error) => {
          console.error(`Error adding student ${studentId} to cohort ${this.cohortId}:`, error);
        }
      });
    }
    this.actualizarData();
  }

  actualizarData() {
    this.ejecutarUpdate.emit();
  }

}

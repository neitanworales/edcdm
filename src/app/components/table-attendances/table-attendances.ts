import { Component, Input, SimpleChanges } from '@angular/core';
import { MatTableDataSource, MatTableModule } from '@angular/material/table';
import { Session, student } from '../../core/models/SessionLesson';
import { StatusAttendance } from '../status-attendance/status-attendance';

@Component({
  selector: 'app-table-attendances',
  imports: [MatTableModule, StatusAttendance],
  templateUrl: './table-attendances.html',
  styleUrl: './table-attendances.css',
  standalone: true  
})
export class TableAttendances {

  @Input()
  students?: student[];

  @Input()
  sessions?: Session[];

  shownColumns: string[] = [];
  datasource: MatTableDataSource<student[]> = new MatTableDataSource<student[]>();
    
  ngOnChanges(changes: SimpleChanges): void {
    if (changes['students']) {
      this.datasource.data = changes['students'].currentValue || [];
    }
    if (changes['sessions']) {
      this.shownColumns = changes['sessions'].currentValue?.map((session: Session) => session.lesson_title) || [];
      console.log('Shown columns updated:', this.shownColumns);
    }
  }

  getAttendanceStatus(student: student, session: Session): boolean {
    const attendance = student.attendances.find(att => att.session_id === session.session_id);
    return attendance ? true : false;
  }

}

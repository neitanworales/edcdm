import { Component, Input } from '@angular/core';
import { AttendanceStatus } from '../../core/models/SessionLesson';
import { AttendancesDao } from '../../core/api/AttendancesDao';

@Component({
  selector: 'app-status-attendance',
  imports: [],
  templateUrl: './status-attendance.html',
  styleUrl: './status-attendance.css',
})
export class StatusAttendance {

  constructor(
    private attendancesDao: AttendancesDao
  ) {}

  @Input() 
  status?: AttendanceStatus;

  @Input()
  attendanceId?: number;

  onStatusChange(event: Event) {
    const inputElement = event.target as HTMLInputElement;
    const isChecked = inputElement.checked;

    console.log(`Attendance ID: ${this.attendanceId}, New status: ${isChecked ? 'presente' : 'ausente'}`);
    this.attendancesDao.updateStatus(this.attendanceId!, isChecked ? 'presente' : 'ausente').subscribe({
      next: (response) => {
        console.log('Status updated successfully', response);
      },
      error: (error) => {
        console.error('Error updating status', error);
      }
    });
  }

}

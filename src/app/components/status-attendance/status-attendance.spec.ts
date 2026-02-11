import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StatusAttendance } from './status-attendance';

describe('StatusAttendance', () => {
  let component: StatusAttendance;
  let fixture: ComponentFixture<StatusAttendance>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [StatusAttendance]
    })
    .compileComponents();

    fixture = TestBed.createComponent(StatusAttendance);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

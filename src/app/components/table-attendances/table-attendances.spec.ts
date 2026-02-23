import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TableAttendances } from './table-attendances';

describe('TableAttendances', () => {
  let component: TableAttendances;
  let fixture: ComponentFixture<TableAttendances>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [TableAttendances]
    })
    .compileComponents();

    fixture = TestBed.createComponent(TableAttendances);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

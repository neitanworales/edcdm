import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PassswordRecovery } from './passsword-recovery';

describe('PassswordRecovery', () => {
  let component: PassswordRecovery;
  let fixture: ComponentFixture<PassswordRecovery>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [PassswordRecovery]
    })
    .compileComponents();

    fixture = TestBed.createComponent(PassswordRecovery);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

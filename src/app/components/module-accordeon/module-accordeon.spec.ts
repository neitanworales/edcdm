import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ModuleAccordeon } from './module-accordeon';

describe('ModuleAccordeon', () => {
  let component: ModuleAccordeon;
  let fixture: ComponentFixture<ModuleAccordeon>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ModuleAccordeon]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ModuleAccordeon);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

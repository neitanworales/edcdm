import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, ReactiveFormsModule } from '@angular/forms';
import { Church } from '../../core/models/Church';
import { ChurchesDao } from '../../core/api/ChurchesDao';
import { ModuleAccordeon } from "../../components/module-accordeon/module-accordeon";
import { Module } from '../../core/models/Module';
import { ModuleDao } from '../../core/api/ModuleDao';
import { SessionsDao } from '../../core/api/SessionsDao';
import { Root } from '../../core/models/SessionLesson';

@Component({
  selector: 'app-attendance',
  imports: [ReactiveFormsModule, ModuleAccordeon],
  templateUrl: './attendance.html',
  styleUrl: './attendance.css',
  standalone: true
})
export class Attendance implements OnInit {
  
  modules?: Module[];
  module?: Module;
  churches?: Church[];
  sessions?: Root;

  filtersForm = new FormGroup({
    church_id: new FormControl<number | null>(null),
    modeality: new FormControl<number | null>(null),
    module: new FormControl<number | null>(null),
  });

  // Selected values for easy access
  selectedChurchId: number | null = null;
  selectedModeality: number | null = null;
  selectedModuleId: number | null = null;
  
  constructor(
    private churchDao: ChurchesDao,
    private moduleDao: ModuleDao,
    private sessionsDao: SessionsDao
  ) {}  

  ngOnInit(): void {
    this.loadChurches();
    this.loadModules();

    this.filtersForm.valueChanges.subscribe(v => {
      this.selectedChurchId = v.church_id ?? -1;
      this.selectedModeality = v.modeality ?? -1;
      this.selectedModuleId = v.module ?? -1;
      console.log('Filter changes:', v);
      this.loadSessions(this.selectedModuleId!, this.selectedChurchId!, this.selectedModeality!);
    });
  }

  loadChurches(): void {
    this.churchDao.list().subscribe((churches) => {
      this.churches = churches;
    });
  }

  loadModules(): void {
    this.moduleDao.list().subscribe((response) => {
      this.modules = response.modules;
    });
  }

  loadSessions(moduleId: number, church_id: number, modeality: number): void {
    this.sessionsDao.list(moduleId, church_id, modeality).subscribe(response => {
      this.sessions = response.response;
      
    });
  }
}
import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, ReactiveFormsModule } from '@angular/forms';
import { Church } from '../../core/models/Church';
import { ChurchesDao } from '../../core/api/ChurchesDao';
import { ModuleAccordeon } from "../../components/module-accordeon/module-accordeon";
import { Module } from '../../core/models/Module';
import { ModuleDao } from '../../core/api/ModuleDao';
import { SessionsDao } from '../../core/api/SessionsDao';
import { Root_SessionLesson } from '../../core/models/SessionLesson';

@Component({
  selector: 'app-attendance',
  imports: [ReactiveFormsModule, ModuleAccordeon],
  templateUrl: './attendance.html',
  styleUrl: './attendance.css',
  standalone: true
})
export class Attendance implements OnInit {
  
  modules?: Module[];
  modulesCombo?: Module[];
  module?: Module;
  churches?: Church[];
  sessions?: Root_SessionLesson;

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
    this.loadModulesCombo();

    this.filtersForm.valueChanges.subscribe(v => {
      this.selectedChurchId = v.church_id ?? null;
      this.selectedModeality = v.modeality ?? null;
      this.selectedModuleId = v.module ?? null;
      console.log('Filter changes:', v);
      // If "Todos" (-1), reload full list
      if (this.selectedModuleId === -1) {
        this.module = undefined;
        this.loadModules();
        return;
      }

      // If a specific module is selected, clear the array and add only that module
      if (this.selectedModuleId && this.selectedModuleId > 0) {
        this.modules = [];
        this.moduleDao.get(this.selectedModuleId).subscribe(response => {
          this.module = response.module;
          this.modules = [];
          this.modules.push(this.module!);
          this.loadSessions(this.selectedModuleId!, this.selectedChurchId!, this.selectedModeality!);
        });
      } else {
        // No selection: show all
        this.module = undefined;
        this.loadModules();
      }
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

  loadModulesCombo(): void {
    this.moduleDao.list().subscribe((response) => {
      this.modulesCombo = response.modules;
    });
  }

  loadSessions(moduleId: number, church_id: number, modeality: number): void {
    this.sessionsDao.list(moduleId, church_id, modeality).subscribe(response => {
      this.sessions = response.sessions;
      
    });
  }
}
import { Component, OnInit } from '@angular/core';
import { Module } from '../../core/models/Module';
import { ModuleDao } from '../../core/api/ModuleDao';
import { NgForOf } from "../../../../node_modules/@angular/common/types/_common_module-chunk";

@Component({
  selector: 'app-class-schedule',
  imports: [],
  templateUrl: './class-schedule.html',
  styleUrl: './class-schedule.css',
  standalone: true
})
export class ClassSchedule implements OnInit {

  modules?: Module[];

  constructor(
    private moduleDao: ModuleDao
  ) { }

  ngOnInit(): void {
    this.loadModules();
  }

  loadModules(): void {
    this.moduleDao.list().subscribe((response) => {
      this.modules = response.modules;
    });
  }
}

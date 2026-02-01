import { Component, Input, OnInit } from '@angular/core';
import { Module } from '../../core/models/Module';
import { Root } from '../../core/models/SessionLesson';

@Component({
  selector: 'app-module-accordeon',
  imports: [],
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

  constructor(
    
  ) { }

  ngOnInit(): void {
    console.log('ModuleAccordeon initialized with modules:', this.modules);
  }

}

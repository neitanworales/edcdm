import { Component } from '@angular/core';
import { AuthService } from '../../core/services/auth.service';
import { RouterLink } from "@angular/router";

@Component({
  selector: 'app-dashboard',
  imports: [RouterLink],
  templateUrl: './dashboard.html',
  styleUrl: './dashboard.css',
})
export class Dashboard {

  constructor(public authService: AuthService) { }

  get displayName(): string {
    const user = this.authService.getUserData();
    return user?.full_name ?? user?.username ?? 'Usuario';
  }

}

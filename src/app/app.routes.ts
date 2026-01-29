import { Routes } from '@angular/router';
import { Home } from './pages/home/home';
import { Login } from './pages/login/login';
import { Churches } from './pages/churches/churches';
import { Dashboard } from './pages/dashboard/dashboard';
import { Register } from './pages/register/register';
import { Usuarios } from './pages/usuarios/usuarios';
import { authGuard } from './core/guards/auth.guard';
import { Attendance } from './pages/attendance/attendance';
import { ClassSchedule } from './pages/class-schedule/class-schedule';

export const routes: Routes = [
    { path: '', redirectTo: 'home', pathMatch: 'full' },
    { path: 'home', component: Home },
    { path: 'login', component: Login },
    { path: 'dashboard', component: Dashboard, canActivate: [authGuard] },
    { path: 'register', component: Register, canActivate: [authGuard] },
    { path: 'churches', component: Churches, canActivate: [authGuard] },
    { path: 'class-schedule', component: ClassSchedule, canActivate: [authGuard] },
    //{ path: 'instructors', component: Instructors, canActivate: [authGuard] },
    //{ path: 'students', component: Students, canActivate: [authGuard] },
    { path: 'attendance', component: Attendance, canActivate: [authGuard] },
    { path: 'users', component: Usuarios, canActivate: [authGuard] },
    { path: '**', redirectTo: 'home' },
];

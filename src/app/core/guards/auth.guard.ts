import { inject } from '@angular/core';
import { Router, CanActivateFn } from '@angular/router';
import { AuthService } from '../services/auth.service';

/**
 * Guard funcional para proteger rutas que requieren autenticación
 */
export const authGuard: CanActivateFn = (route, state) => {
  const authService = inject(AuthService);
  const router = inject(Router);

  if (authService.isAuthenticated()) {
    console.log('Usuario autenticado, acceso permitido a la ruta:', state.url);
    return true;
  }

  // Redirige al login si no está autenticado
  router.navigate(['/login'], {
    queryParams: { returnUrl: state.url }
  });
  return false;
};

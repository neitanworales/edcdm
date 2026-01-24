import { Component, ChangeDetectorRef } from '@angular/core';
import { UserDao } from '../../core/api/UsuarioDao';
import { AppUser } from '../../core/models/AppUser';
import { ReactiveFormsModule } from '@angular/forms';
import { FormControl, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-usuarios',
  imports: [ReactiveFormsModule],
  templateUrl: './usuarios.html',
  styleUrl: './usuarios.css',
  standalone: true,
})
export class Usuarios {
  users: AppUser[] = [];
  roles: Array<AppUser['role']> = ['admin', 'instructor', 'viewer', 'auxiliar'];
  editingId: number | null = null;
  alertMessage: string | null = null;
  alertType: 'success' | 'danger' | 'info' | 'warning' = 'success';

  userForm = new FormGroup({
    username: new FormControl<string>('', { nonNullable: true, validators: [Validators.required, Validators.email, Validators.maxLength(100)] }),
    full_name: new FormControl<string>('', { validators: [Validators.maxLength(200)] }),
    role: new FormControl<AppUser['role']>('viewer', { nonNullable: true }),
    password: new FormControl<string>('', { validators: [Validators.minLength(6)] }),
  });

  constructor(
    private userDao: UserDao,
    private cdr: ChangeDetectorRef
  ) { }

  ngOnInit(): void {
    this.loadData();
  }

  loadData() {
    this.userDao.list().subscribe({
      next: (response) => {
        this.users = Array.isArray(response.users) ? [...response.users] : [];
        console.log('Users loaded:', this.users);
        // Ensure change detection runs after full page reload
        this.cdr.detectChanges();
      },
      error: (error) => {
        console.error('Error fetching users:', error);
      }
    });
  }

  submit(): void {
    if (this.userForm.invalid) {
      this.userForm.markAllAsTouched();
      return;
    }
    const { username, full_name, role, password } = this.userForm.getRawValue();

    if (this.editingId) {
      const updatePayload: any = { username, full_name, role };
      if (password) updatePayload.password = password;
      this.userDao.update(this.editingId, updatePayload).subscribe({
        next: () => {
          this.users = this.users?.map(u => u.id === this.editingId ? { ...u, username: username!, full_name: full_name || undefined, role: role! } : u);
          this.cancel();
          this.show('Usuario actualizado correctamente.', 'success');
        },
        error: (err) => this.show('Error al actualizar usuario.', 'danger')
      });
    } else {
      if (!password) {
        this.show('La contraseña es requerida para crear un usuario.', 'warning');
        return;
      }
      this.userDao.create({ username: username!, password, full_name: full_name || undefined, role: role! }).subscribe({
        next: (res) => {
          const created: AppUser = { id: res.id, username: username!, full_name: full_name || undefined, role: role! };
          this.users = [created, ...this.users!];
          this.userForm.reset({ role: 'viewer' });
          this.show('Usuario creado correctamente.', 'success');
        },
        error: (err) => this.show('Error al crear usuario.', 'danger')
      });
    }
  }

  startEdit(user: AppUser): void {
    this.editingId = user.id ?? null;
    this.userForm.reset({
      username: user.username,
      full_name: user.full_name ?? '',
      role: user.role,
      password: ''
    });
  }

  cancel(): void {
    this.editingId = null;
    this.userForm.reset({ role: 'viewer' });
  }

  remove(id?: number): void {
    if (!id) return;
    if (!confirm('¿Seguro que quieres eliminar este usuario?')) return;
    this.userDao.delete(id).subscribe({
      next: () => {
        this.users = this.users?.filter(u => u.id !== id);
        this.loadData();
        if (this.editingId === id) this.cancel();
        this.show('Usuario eliminado correctamente.', 'success');
      },
      error: (err) => this.show('Error al eliminar usuario.', 'danger')
    });
  }

  private show(msg: string, type: 'success' | 'danger' | 'info' | 'warning' = 'success') {
    this.alertMessage = msg;
    this.alertType = type;
    setTimeout(() => this.alertMessage = null, 4000);
  }

  formatDate(value?: string): string {
    if (!value) return '-';
    try {
      const d = new Date(value);
      return isNaN(d.getTime()) ? value : d.toLocaleString();
    } catch {
      return value;
    }
  }


}

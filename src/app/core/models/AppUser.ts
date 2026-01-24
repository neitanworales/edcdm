export interface AppUser {
  id?: number;
  username: string;
  full_name?: string;
  role: 'admin' | 'instructor' | 'viewer' | 'auxiliar';
  created_at?: string;
  updated_at?: string;
}
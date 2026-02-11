export interface Root {
  module_id: number
  church_id: number
  modality_id: number
  churches: Church[]
  message: string
}

export interface Church {
  church_id: number
  church: string
  modalities: Modality[]
}

export interface Modality {
  modality_id: number
  label: string
  modules: Module[]
}

export interface Module {
  module_id: number
  code: string
  module_title: string
  cohort_id: number
  sessions: Session[]
  students: student[]
}

export interface Session {
  session_id: number
  session_datetime: string
  lesson_id: number
  lesson_number: number
  lesson_title: string
}

export interface student {
  student_id?: number;
  first_name?: string;
  last_name?: string;
  phone?: string;
  attendances: attendances[]
}

export interface attendances {
  attendance_id?: number;
  student_id?: number;
  first_name?: string;
  last_name?: string;
  session_id?: number;
  status?: AttendanceStatus;
}

export enum AttendanceStatus {
  Presente = 'presente',
  Ausente = 'ausente',
  Justificado = 'justificado',
  Pendiente = '-',
}
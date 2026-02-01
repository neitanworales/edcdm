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
  sessions: Session[]
}

export interface Session {
  session_id: number
  session_datetime: string
  lesson_id: number
  lesson_number: number
  lesson_title: string
}
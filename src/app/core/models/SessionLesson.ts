export interface Root_SessionLesson {
  module_id: number
  church_id: number
  modality_id: number
  churches: Church_SessionLesson[]
  message: string
}

export interface Church_SessionLesson {
  church_id: number
  church: string
  modalities: Modality_SessionLesson[]
}

export interface Modality_SessionLesson {
  modality_id: number
  label: string
  sessions: Session_SessionLesson[]
}

export interface Session_SessionLesson {
  session_id: number
  session_datetime: string
  lesson_id: number
  lesson_number: number
  lesson_title: string
}

import { Root_SessionLesson } from "../SessionLesson";
import { DefaultResponse } from "./DefaultResponse";

export class SessionLessonResponse extends DefaultResponse {
    sessions?: Root_SessionLesson;
}
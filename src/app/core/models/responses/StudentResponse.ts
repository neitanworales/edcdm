import { Student } from "../Student";
import { DefaultResponse } from "./DefaultResponse";

export class StudentResponse extends DefaultResponse {
    students!: Student;
    student!: Student;
}
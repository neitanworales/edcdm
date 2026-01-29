import { HttpClient } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { Observable } from "rxjs";
import { environment } from "../../../environments/environment";
import { Utils } from "./Utils";
import { Student } from "../models/Student";
import { StudentResponse } from "../models/responses/StudentResponse";
import { DefaultResponse } from "../models/responses/DefaultResponse";

@Injectable({ providedIn: "root" })
export class StudentDao {
  constructor(
    private http: HttpClient,
    private utils: Utils
  ) {}

  list(): Observable<StudentResponse> {
    return this.http.get<StudentResponse>(environment.api + "students", { headers: this.utils.getHeaders() });
  }

  get(id: number): Observable<StudentResponse> {
    return this.http.get<StudentResponse>(environment.api + "students/" + id, { headers: this.utils.getHeaders() });
  }

  create(student: Student): Observable<DefaultResponse> {
    return this.http.post<DefaultResponse>(environment.api + "students", student, { headers: this.utils.getHeaders() });
  }

  update(id: number, student: Student): Observable<DefaultResponse> {
    return this.http.put<DefaultResponse>(environment.api + "students/" + id, student, { headers: this.utils.getHeaders() });
  }

  delete(id: number): Observable<DefaultResponse> {
    return this.http.delete<DefaultResponse>(environment.api + "students/" + id, { headers: this.utils.getHeaders() });
  }
}

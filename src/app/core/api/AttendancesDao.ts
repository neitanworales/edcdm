import { HttpClient } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { Observable } from "rxjs";
import { environment } from "../../../environments/environment";
import { Utils } from "./Utils";
import { DefaultResponse } from "../models/responses/DefaultResponse";

@Injectable({ providedIn: 'root' })
export class AttendancesDao {
  constructor(
    private http: HttpClient,
    private utils: Utils
  ) {}

  updateStatus(attendanceId: number, newStatus: string): Observable<DefaultResponse> {
    return this.http.put<DefaultResponse>(environment.api + 'attendances/' + attendanceId + '/status/' + newStatus, {}, { headers: this.utils.getHeaders() });
  }
}

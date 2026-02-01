import { HttpClient } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { Observable } from "rxjs";
import { environment } from "../../../environments/environment";
import { Utils } from "./Utils";
import { SessionLessonResponse } from "../models/responses/SessionLessonResponse";

@Injectable({ providedIn: 'root' })
export class SessionsDao {
    constructor(
        private http: HttpClient,
        private utils: Utils
    ) {}

    list(idModule: number, church_id: number, modeality: number): Observable<SessionLessonResponse> {
        return this.http.get<SessionLessonResponse>(environment.api + `/modules/${idModule}/sessions?church_id=${church_id}&modeality=${modeality}`, { headers: this.utils.getHeaders() });
    }
}
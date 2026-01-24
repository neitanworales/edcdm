import { HttpClient } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { Observable } from "rxjs";
import { Utils } from "./Utils";
import { environment } from "../../../environments/environment";
import { Church } from "../models/Church";

@Injectable({ providedIn: 'root' })
export class ChurchesDao {
  constructor(
    private http: HttpClient,
    private utils: Utils
  ) {}

  list(): Observable<Church[]> {
    return this.http.get<Church[]>(environment.api + 'churches', { headers: this.utils.getHeaders() });
  }

  create(church: Church): Observable<{ id: number }> {
    return this.http.post<{ id: number }>(environment.api + 'churches', church, { headers: this.utils.getHeaders() });
  }

  get(id: number): Observable<Church> {
    return this.http.get<Church>(environment.api + 'churches/' + id, { headers: this.utils.getHeaders() });
  }

  update(id: number, church: Church): Observable<{ updated: boolean }> {
    return this.http.put<{ updated: boolean }>(environment.api + 'churches/' + id, church, { headers: this.utils.getHeaders() });
  }

  delete(id: number): Observable<{ deleted: boolean }> {
    return this.http.delete<{ deleted: boolean }>(environment.api + 'churches/' + id, { headers: this.utils.getHeaders() });
  }
}

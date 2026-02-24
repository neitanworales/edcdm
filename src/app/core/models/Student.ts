import { Church } from "./Church";

export interface Student {
    id: number;
    first_name: string;
    last_name: string;
    email?: string;
    phone?: string;
    church_id?: number;
    date_of_birth?: string;
    notes?: string;
    church?: Church
}
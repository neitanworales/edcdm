import { Lesson } from "./Lesson";

export class Module {
    id?: number;
    code?: string;
    title?: string;
    description?: string;
    recommended_classes?: number;
    lessons?: Lesson[]
}
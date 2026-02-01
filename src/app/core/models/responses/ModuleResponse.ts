import { Module } from "../Module";
import { DefaultResponse } from "./DefaultResponse";

export class ModuleResponse extends DefaultResponse {
    modules?: Module[];
    module?: Module;
}
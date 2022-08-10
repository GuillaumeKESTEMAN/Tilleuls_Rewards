import {RaRecord} from "react-admin";

export class MediaObject {
    constructor(
        _id?: string,
        public id?: string,
        public name?: string,
        public contentUrl?: string,
        public file?: any,
        public filePath?: string,
    ) {
        this['@id'] = _id;
    }

    public '@id'?: string;
}

export interface MediaObjectRaRecord extends RaRecord {
    name: string;
    contentUrl?: string;
    file?: any;
    filePath?: string;
}

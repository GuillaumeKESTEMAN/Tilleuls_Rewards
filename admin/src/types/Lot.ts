import {RaRecord} from "react-admin";

export class Lot {
    public '@id'?: string;

    constructor(
        _id?: string,
        public id?: string,
        public name?: string,
        public quantity?: number,
        public message?: string,
        public image?: any,
    ) {
        this['@id'] = _id;
    }
}

export interface LotRaRecord extends RaRecord {
    originId?: string;
    name?: string;
    quantity?: number;
    message?: string;
    image?: any;
}

import {RaRecord} from "react-admin";

export class Player {
    public '@id'?: string;

    constructor(
        _id?: string,
        public id?: string,
        public name?: string,
        public username?: string,
        public twitterAccountId?: string,
        public lastPlayDate?: Date,
        public tweets?: any,
    ) {
        this['@id'] = _id;
    }
}

export interface PlayerRaRecord extends RaRecord {
    name?: string;
    username?: string;
    twitterAccountId?: string;
    lastPlayDate?: Date;
    tweets?: any;
}

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

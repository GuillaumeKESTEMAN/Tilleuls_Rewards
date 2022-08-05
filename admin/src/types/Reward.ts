export class Reward {
    public '@id'?: string;

    constructor(
        _id?: string,
        public id?: string,
        public lot?: any,
        public distributed?: boolean,
    ) {
        this['@id'] = _id;
    }
}

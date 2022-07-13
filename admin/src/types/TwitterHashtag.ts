export class TwitterHashtag {
    constructor(
        _id?: string,
        public id?: string,
        public hashtag?: string,
        public active?: boolean,
    ) {
        this['@id'] = _id;
    }

    public '@id'?: string;
}

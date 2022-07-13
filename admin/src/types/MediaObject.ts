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

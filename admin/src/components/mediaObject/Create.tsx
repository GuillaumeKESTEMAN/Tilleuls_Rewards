import {FunctionComponent} from "react";
import {MediaObject} from "../../types/MediaObject";
import {CreateGuesser, InputGuesser} from "@api-platform/admin";
import {ImageInput, ImageField} from "react-admin";

interface Props {
    mediaObject: MediaObject;
}

const PreviewImage = ({record, source}) => {
    if (typeof (record) === "string") {
        record = {
            [source]: record
        }
    }
    return <ImageField record={record} source={source}/>
}

export const Create: FunctionComponent<Props> = ({mediaObject}) => (
    <CreateGuesser {...mediaObject}>
        <InputGuesser source="name"/>
        <ImageInput source="file" name="file" accept={'image/*'}>
            <PreviewImage source="src"/>
        </ImageInput>
    </CreateGuesser>
);

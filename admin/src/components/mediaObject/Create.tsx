import {FunctionComponent} from "react";
import {MediaObject} from "../../types/MediaObject";
import {CreateGuesser, InputGuesser} from "@api-platform/admin";
import {FileField, FileInput} from "react-admin";

interface Props {
    mediaObject: MediaObject;
}

export const Create: FunctionComponent<Props> = ({mediaObject}) => (
    <CreateGuesser {...mediaObject}>
        <InputGuesser source="name"/>
        <FileInput source="file" name="file">
            <FileField source="src" title="media_object"/>
        </FileInput>
    </CreateGuesser>
);

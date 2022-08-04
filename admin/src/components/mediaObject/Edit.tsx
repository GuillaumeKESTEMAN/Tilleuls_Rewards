import {FunctionComponent} from "react";
import {MediaObject} from "../../types/MediaObject";
import {EditGuesser, InputGuesser} from "@api-platform/admin";

interface Props {
    mediaObject: MediaObject;
}

export const Edit: FunctionComponent<Props> = ({mediaObject}) => (
    <EditGuesser {...mediaObject}>
        <InputGuesser source="name" disabled/>
    </EditGuesser>
);

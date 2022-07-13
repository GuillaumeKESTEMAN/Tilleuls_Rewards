import {FunctionComponent} from "react";
import {MediaObject} from "../../types/MediaObject";
import {EditGuesser, InputGuesser} from "@api-platform/admin";
import {AutocompleteInput, ReferenceInput} from "react-admin";

interface Props {
    mediaObject: MediaObject;
}

// @ts-ignore
export const Edit: FunctionComponent<Props> = ({mediaObject}) => (
    <EditGuesser {...mediaObject}>
        <InputGuesser disabled={true} source="name"/>
    </EditGuesser>
);

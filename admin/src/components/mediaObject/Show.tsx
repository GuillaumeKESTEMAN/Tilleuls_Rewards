import {FunctionComponent} from "react";
import {MediaObject} from "../../types/MediaObject";
import {FieldGuesser, ShowGuesser} from "@api-platform/admin";

interface Props {
    mediaObject: MediaObject;
}

export const Show: FunctionComponent<Props> = ({mediaObject}) => {
    return (
        <ShowGuesser {...mediaObject}>
            <FieldGuesser source="name"/>
        </ShowGuesser>
    );
}

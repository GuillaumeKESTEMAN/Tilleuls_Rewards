import {FunctionComponent} from "react";
import {MediaObject} from "../../types/MediaObject";
import {FieldGuesser, ListGuesser} from "@api-platform/admin";

interface Props {
    mediaObjects: MediaObject[];
}

// @ts-ignore
export const List: FunctionComponent<Props> = ({mediaObjects}) => (
    <ListGuesser rowClick="show" {...mediaObjects}>
        <FieldGuesser source="name"/>
    </ListGuesser>
);

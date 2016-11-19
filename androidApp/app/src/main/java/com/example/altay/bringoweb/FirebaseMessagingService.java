package com.example.altay.bringoweb;

/**
 * Created by altay on 04/10/2016.
 */

import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.location.Location;
import android.media.RingtoneManager;
import android.os.Bundle;
import android.support.v4.app.NotificationCompat;
import android.util.Log;

import com.google.android.gms.location.LocationRequest;
import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.messaging.RemoteMessage;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.location.LocationServices;
import com.google.android.gms.location.LocationListener;

import java.io.IOException;

import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;

public class FirebaseMessagingService extends com.google.firebase.messaging.FirebaseMessagingService implements GoogleApiClient.ConnectionCallbacks,
        GoogleApiClient.OnConnectionFailedListener,
        LocationListener {

    private static final String SERVER_DOMAIN = "bringoapp.com";
    private boolean thread_running = true;
    private String Token;
    private GoogleApiClient mGoogleApiClient;
    private LocationRequest mLocationRequest;
    private static String location = "Null";
    private static Location realLoc ;

    public void initGoogleApiClient() {

        mGoogleApiClient = new GoogleApiClient.Builder(this)
                .addApi(LocationServices.API)
                .addConnectionCallbacks(this)
                .addOnConnectionFailedListener(this)
                .build();

        mGoogleApiClient.connect();
    }

    @Override
    public void onMessageReceived(RemoteMessage remoteMessage) {
        initGoogleApiClient();
        handleMessage(remoteMessage);
    }

    private void handleMessage(RemoteMessage remoteMessage) {

        final String type = remoteMessage.getData().get("type");

        if (type.equals("notification")) {

            String[] data = null;
            String message = remoteMessage.getData().get("data");

            if (!message.isEmpty()) {
                data = message.split(";;");
            }

            Intent i = new Intent(this, MainActivity.class);
            i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

            PendingIntent pendingIntent = PendingIntent.getActivity(this, 0, i, PendingIntent.FLAG_UPDATE_CURRENT);

            NotificationCompat.Builder builder = new NotificationCompat.Builder(this)
                    .setAutoCancel(true)
                    .setContentTitle(data[0])
                    .setContentText(data[1])
                    .setSmallIcon(R.mipmap.ic_launcher)
                    .setSound(RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION))
                    .setVibrate(new long[]{0, 300, 200, 300, 0})
                    .setContentIntent(pendingIntent);

            NotificationManager manager = (NotificationManager) getSystemService(NOTIFICATION_SERVICE);
            manager.notify(0, builder.build());

        } else if (type.equals("reportPos")) {

            if (!mGoogleApiClient.isConnected()) {
                System.out.println("debug");
                mGoogleApiClient.connect();
            }

            Thread t = new Thread(new Runnable() {
                @Override
                public void run() {
                    while (thread_running) {
                        Token = FirebaseInstanceId.getInstance().getToken();

                        if (Token != null) {

                            System.out.println("Reported: " + location);
                            OkHttpClient client = new OkHttpClient();
                            RequestBody body = new FormBody.Builder()
                                    .add("token", Token)
                                    .add("type", "reportPos")
                                    .add("lng",String.valueOf(realLoc.getLongitude()))
                                    .add("lat", String.valueOf(realLoc.getLatitude()))
                                    .add("acc",String.valueOf(realLoc.getAccuracy()))
                                    .build();

                            Request request = new Request.Builder()
                                    .url("http://smartsail.esy.es/app/api.php")
                                    .post(body)
                                    .build();

                            try {
                                client.newCall(request).execute();
                            } catch (IOException e) {
                                e.printStackTrace();
                            }

                            thread_running = false;
                        } else {
                            System.out.println("- Token not loaded -");
                        }
                        try {
                            Thread.sleep(1000);
                        } catch (InterruptedException e) {
                            e.printStackTrace();
                        }
                    }
                }
            });

            if(realLoc != null){
                t.start();
            }

        }

    }

    @Override
    public void onConnected(Bundle bundle) {

        mLocationRequest = LocationRequest.create();
        mLocationRequest.setPriority(LocationRequest.PRIORITY_HIGH_ACCURACY);
        //mLocationRequest.setInterval(1000); // Update location every second

        if (checkSelfPermission(android.Manifest.permission.ACCESS_FINE_LOCATION) == PackageManager.PERMISSION_GRANTED
                || checkSelfPermission(android.Manifest.permission.ACCESS_COARSE_LOCATION) == PackageManager.PERMISSION_GRANTED) {

            LocationServices.FusedLocationApi.requestLocationUpdates(
                    mGoogleApiClient, mLocationRequest, this);
        }
    }

    @Override
    public void onConnectionSuspended(int i) {
        Log.e("Err", "GoogleApiClient connection has been suspend");
    }

    @Override
    public void onConnectionFailed(ConnectionResult connectionResult) {
        Log.e("Err", "GoogleApiClient connection has failed");
    }

    @Override
    public void onLocationChanged(Location location) {
        realLoc = location;
        this.location = location.getLatitude() + "," + location.getLongitude() + ";" + location.getAccuracy();
        Log.e("Location received: ", this.location.toString());
    }
}
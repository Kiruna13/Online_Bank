package com.example.banqueenligne

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.view.Window

class Connexion : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        requestWindowFeature(Window.FEATURE_NO_TITLE)
        supportActionBar?.hide()
        setContentView(R.layout.connexion)
    }
}